<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Template;
use App\Models\TemplateInputFields;
use App\Models\GeneratedContent;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\User;

class TemplateController extends Controller
{
    public function AdminTemplate(){
        $templates = Template::latest()->get();
        return view('admin.backend.template.all_template',compact('templates'));
    }
    //End Method

    public function AddTemplate(){
        return view('admin.backend.template.add_template');
    }
    //End Method

    public function StoreTemplate(Request $request){

        /// Validate the request data
        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',

            'icon' => 'required|string',
            'prompt' => 'required|string',
            'is_active' => 'required|in:0,1',

            'input_fields' => 'required|array|size:1',
            'input_fields.*.title' => 'required|string|max:255',
            'input_fields.*.description' => 'required|string',
            'input_fields.*.type' => 'required|in:text,textarea',
        ]);

        // Create the template
        $template = new Template();
        $template->title = $validateData['title'];
        $template->description = $validateData['description'];
        $template->category = $validateData['category'];
        $template->icon = $validateData['icon'];
        $template->prompt = $validateData['prompt'];
        $template->is_active = $validateData['is_active'];

        $template->created_by = Auth::id();
        $template->save();

        // Save data in Input Filed table
        $inputField = $validateData['input_fields'][0];
        TemplateInputFields::create([
            'template_id' => $template->id,
            'title' => $inputField['title'],
            'description' => $inputField['description'],
            'type' => $inputField['type'],
            'is_required' => true,
            'order' => 0,
        ]);

        $notification = array(
            'message' => 'Template Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.template')->with($notification);
    }
    //End Method

    public function EditTemplate($id){
        $template = Template::with('inputFields')->findOrFail($id);
        return view('admin.backend.template.edit_template',compact('template'));
    }
    //End Method


    public function UpdateTemplate(Request $request, $id){

        /// Validate the request data
        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',

            'icon' => 'required|string',
            'prompt' => 'required|string',
            'is_active' => 'required|in:0,1',

            'input_fields' => 'required|array|size:1',
            'input_fields.*.title' => 'required|string|max:255',
            'input_fields.*.description' => 'required|string',
            'input_fields.*.type' => 'required|in:text,textarea',
        ]);

        // Create the template
        $template = Template::findOrFail($id);
        $template->title = $validateData['title'];
        $template->description = $validateData['description'];
        $template->category = $validateData['category'];
        $template->icon = $validateData['icon'];
        $template->prompt = $validateData['prompt'];
        $template->is_active = $validateData['is_active'];
        $template->save();

        // Save data in Input Filed table
        $inputField = $validateData['input_fields'][0];

        $templateInputField = TemplateInputFields::where('template_id',$template->id)->first();

        if ($templateInputField ) {
            $templateInputField->title = $inputField['title'];
            $templateInputField->description = $inputField['description'];
            $templateInputField->type = $inputField['type'];
            $templateInputField->is_required = true;
            $templateInputField->order = 0;
            $templateInputField->save();
        }

        $notification = array(
            'message' => 'Template Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.template')->with($notification);
    }
    //End Method


    public function DetailsTemplate($id){
        $template = Template::with('inputFields')->findOrFail($id);
        $user = Auth::user();
        return view('admin.backend.template.details_template',compact('template','user'));
    }
    //End Method

    public function AdminContentGenerate(Request $request, $id){

        // Fetch the template with its input fields
        $template = Template::with('inputFields')->findOrFail($id);
        $user = auth()->user();

        /// Validate request
        $validateData = $request->validate([
            'language' => 'required|string|in:English (USA),Bengali (Bangladesh),Urdu (Pakistan),Hindi (India),French (France),Turkish (Turkey)',
            'ai_model' => 'required|string|in:gpt-4,gpt-3.5-turbo',
            'result_length' => 'required|integer|min:50|max:1000',
        ]);

        /// Validate dynamic input fields
        foreach($template->inputFields as $field) {
            // Normalize field name to match form input names (remove special characters)
            $fieldName = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ','_',$field->title));
            $request->validate([
                $fieldName => 'required|string',
            ]);
        }

        // Get user input for dynamic fields
        $inputData = $request->except(['_token','language','ai_model','result_length']);
        \Log::info('Input Data', ['inputData' => $inputData]); // Debug input data

        $prompt = $template->prompt;

        /// Replace placeholders with user input (normalized field names)
        foreach($template->inputFields as $field) {
            // Normalize field name: replace spaces with underscores and remove special characters
            $normalizedFieldName = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ','_',$field->title));

            // Get the field value from input data
            $fieldValue = $inputData[$normalizedFieldName] ?? '';

            // Replace placeholder in prompt (normalized format)
            $prompt = str_replace('{' . $normalizedFieldName . '}', $fieldValue, $prompt);

            \Log::info('Replacing placeholder', [
                'field_title' => $field->title,
                'normalized_name' => $normalizedFieldName,
                'field_value' => $fieldValue,
                'placeholder' => '{' . $normalizedFieldName . '}'
            ]);
        }

        /// Replace result_length placeholder
        $prompt = str_replace('{result_length}', $validateData['result_length'], $prompt);

        /// Build a better structured prompt
        $prompt = trim($prompt) . "\n\n" .
                  "Requirements:\n" .
                  "- Write the content in {$validateData['language']}\n" .
                  "- Target length: approximately {$validateData['result_length']} words\n" .
                  "- Make the content well-structured, professional, and engaging\n" .
                  "- Use proper formatting with paragraphs and headings where appropriate";

        \Log::info('Final Prompt', ['prompt' => $prompt]);

        /// Check word limit
        $estimatedWordCount = $validateData['result_length'];
        if ($user->words_used + $estimatedWordCount > $user->current_word_usage) {
            return response()->json([
                'success' => false,
                'message' => 'Word limit exceeded. You have ' . ($user->current_word_usage - $user->words_used) . ' words remaining.',
            ],400);
        }

        try {
            /// Calculate max tokens based on desired word count (words * 1.5 for safety)
            $maxTokens = min((int)($validateData['result_length'] * 1.5), 4000);

            //Generate content with OpenAI with better parameters
            $response = OpenAI::chat()->create([
                'model'=> $validateData['ai_model'],
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a professional content writer who creates high-quality, well-structured content based on user requirements.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7, // Balanced creativity
                'max_tokens' => $maxTokens, // Control output length
                'top_p' => 1,
                'frequency_penalty' => 0.3, // Reduce repetition
                'presence_penalty' => 0.3, // Encourage topic diversity
            ]);

            $output = $response->choices[0]->message->content;
            $wordCount = str_word_count($output);

            /// Update user word usage
            $user->words_used += $wordCount;
            $user->save();

            // SAVE DATA TO GENERATED CONTENT TABLE
            GeneratedContent::create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'input' => json_encode($inputData),
                'output' => $output,
                'word_count' => $wordCount,
            ]);

            \Log::info('Content Generated Successfully', [
                'word_count' => $wordCount,
                'user_words_remaining' => $user->current_word_usage - $user->words_used
            ]);

            return response()->json([
                'success' => true,
                'output' => $output,
                'word_count' => $wordCount,
                'words_remaining' => $user->current_word_usage - $user->words_used
            ]);

        } catch (\Exception $e) {
            \Log::error('Content Generation Failed', [
                'error' => $e->getMessage(),
                'template_id' => $template->id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content: ' . $e->getMessage(),
            ],500);
        }

    }
    //End Method



}
