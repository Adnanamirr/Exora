<?php

namespace App\Http\Controllers\Backend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Template;
use App\Models\TemplateInputFields;
use App\Models\GeneratedContent;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\User;

class UserTemplateController extends Controller
{
    public function UserTemplate(){
        $user = Auth::user();
        $userPlan = $user->plan;
        $templateLimit = $userPlan ? $userPlan->templates : 3;

        $templates = Template::latest()->take($templateLimit)->get();
        return view('client.backend.template.all_template',compact('user','templates'));
    }


    public function UserDetailsTemplate($id){
        $template = Template::with('inputFields')->findOrFail($id);
        $user = Auth::user();
        return view('client.backend.template.details_template',compact('template','user'));

    }

    public function UserContentGenerate(Request $request, $id){

        // Fetch the template with its input fields
        $template = Template::with('inputFields')->findOrFail($id);
        $user = auth()->user();

        /// Validate request
        $validateData = $request->validate([
            'language' => 'required|string|in:English (USA),Bangla (Bangladesh),Urdu (Pakistan),Hindi (India),French (France),Turkish (Turkey)',
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

    public function UserDocument(){
        $id = Auth::user()->id;
        $document = GeneratedContent::where('user_id',$id)->orderBy('id','desc')->get();
        return view('client.backend.document.all_document',compact('document'));
    }

    public function EditUserDocument($id){
        $document = GeneratedContent::findOrFail($id);
        return view('client.backend.document.edit_document',compact('document'));
    }
    /// End Method

    public function UserUpdateDocument(Request $request, $id){
        $document = GeneratedContent::findOrFail($id);

        $validateData = $request->validate([
            'output' => 'required|string',
        ]);

        $document->update([
            'output' => $validateData['output'],
        ]);

        $notification = array(
            'message' => 'Document Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('user.document')->with($notification);
    }

    public function DeleteUserDocument($id){

        GeneratedContent::find($id)->delete();
        $notification = array(
            'message' => 'Document Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    // End Method
}
