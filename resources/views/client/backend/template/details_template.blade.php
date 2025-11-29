@extends('client.client_dashboard')
@section('client')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .nk-editor {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
    </style>

    <div class="nk-content-inner">
        <div class="nk-content-body">

            <div class="nk-block-head nk-page-head">
                <div class="nk-block-head-between">
                    <div class="nk-block-head-content">
                        <h2 class="display-6"> {{ $template->title  }}   </h2>
                        <p> {{ $template->description  }}</p>
                    </div>
                </div>
            </div><!-- .nk-page-head -->

            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        {{-- Left Sidebar  --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <p><strong>Your Balance Is {{ $user->current_word_usage - $user->words_used }} Words Left </strong></p>
                            </div>

                            <form id="generateForm" action="{{ route('user.content.generate',$template->id) }}" method="post" enctype="multipart/form-data">

                            @csrf

                                <div class="form-group">
                                    <label for="language" class="form-label">Language  </label>
                                    <div class="form-control-wrap">
                                        <select name="language" class="form-select" id="language" >
                                            <option value="English (USA)">English (USA)</option>
                                            <option value="Bengali (Bangladesh)">Bengali (Bangladesh)</option>
                                            <option value="Urdu (Pakistan)">Urdu (Pakistan)</option>
                                            <option value="Hindi (India)">Hindi (India)</option>
                                            <option value="French (France)">French (France)</option>
                                            <option value="Turkish (Turkey)">Turkish (Turkey)</option>
                                        </select>
                                    </div>
                                </div>

                                @foreach ($template->inputFields as $field)
                                    @php
                                        // Normalize field name: replace spaces with underscores and remove special characters (?, !, etc.)
                                        $normalizedFieldName = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $field->title));
                                    @endphp
                                    <div class="form-group mt-3">
                                        <label for="{{ $normalizedFieldName }}">{{ $field->title }}</label>

                                        @if ($field->type === 'text')
                                            <input type="text" name="{{ $normalizedFieldName }}" id="{{ $normalizedFieldName }}" class="form-control" placeholder="Enter {{ strtolower($field->title) }}" required>

                                        @elseif ($field->type === 'textarea')

                                            <textarea name="{{ $normalizedFieldName }}" id="{{ $normalizedFieldName }}"  rows="5" class="form-control" placeholder="Enter {{ strtolower($field->title) }}" required></textarea>
                                        @endif
                                        <small>{{ $field->description }}</small>

                                    </div>

                                @endforeach

                                <div class="form-group mt-3">
                                    <label for="ai_model" class="form-label">AI Model  </label>
                                    <div class="form-control-wrap">
                                        <select name="ai_model" class="form-select" id="ai_model" >
                                            <option value="gpt-4">OpenAI | GPT 4</option>
                                            <option value="gpt-3.5-turbo">OpenAI | GPT-3.5-turbo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="result_length" class="form-label">Extimated Result Length </label>
                                            <div class="form-control-wrap">
                                                <input type="number" name="result_length" class="form-control" id="result_length" value="200" min="50" max="1000" required >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3 ">Generate</button>


                            </form>
                        </div>
                        {{-- End Left Sidebar  --}}


                        {{-- Right Sidebar  --}}
                        <div class="col-md-8">
                            <div class="nk-editor">
                                <div class="nk-editor-header">
                                    <div class="nk-editor-title">
                                        <h4 class="me-3 mb-0 line-clamp-1">{{ $template->title  }}</h4>
                                        <ul class="d-inline-flex align-item-center">

                                            <li>
                                                <button class="btn btn-sm btn-icon btn-zoom">
                                                    <em class="icon ni ni-star"></em>
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="nk-editor-tools d-none d-xl-flex">
                                        <ul class="d-inline-flex gap gx-3 gx-lg-4 pe-4 pe-lg-5">
                                            <li>
                                                <span class="sub-text text-nowrap">Words <span class="text-dark" id="word-count">0</span></span>
                                            </li>
                                            <li>
                                                <span class="sub-text text-nowrap">Characters <span class="text-dark" id="char-count" >0</span></span>
                                            </li>
                                        </ul>
                                        <ul class="d-inline-flex gap gx-3">
                                            <li>
                                                <div class="dropdown">
                                                    <button class="btn btn-md btn-light rounded-pill" type="button" data-bs-toggle="dropdown">
                                                        <span>Export</span>
                                                        <em class="icon ni ni-chevron-down"></em>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="#" class="dropdown-item" id="copy-text">
                                                                <em class="icon ni ni-copy"></em>
                                                                <span>Copy Text</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="dropdown-item" id="export-text">
                                                                <em class="icon ni ni-file-text"></em>
                                                                <span>Export as Text</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="dropdown-item" id="export-html">
                                                                <em class="icon ni ni-code"></em>
                                                                <span>Export as HTML</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <button class="btn btn-md btn-primary rounded-pill" type="button" id="save-content">
                                                    <em class="icon ni ni-save"></em>
                                                    <span>Save</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="nk-editor-main">

                                    <div class="nk-editor-body">
                                        <div class="wide-md h-100">
                                            <div class="js-editor nk-editor-style-clean nk-editor-full" data-menubar="false" >
                                                <div id="editor-v1">  </div>
                                            </div> <!-- .js-editor -->
                                        </div>
                                    </div><!-- .nk-editor-body -->
                                </div><!-- .nk-editor-main -->
                            </div>



                        </div>
                        {{-- End Right Sidebar  --}}

                    </div>
                </div>
            </div>



        </div>
    </div>

    <script>
        // Handle form submission with AJAX
        document.getElementById('generateForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const form = this;
            const formData = new FormData(form);
            const generateBtn = form.querySelector('button[type="submit"]');

            // Disable button and show loading state
            if (generateBtn) {
                generateBtn.disabled = true;
                generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
            }

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Response Data:', data); // Debug
                    if (data.success) {
                        const editor = document.getElementById('editor-v1');
                        if (editor) {
                            // Use the improved formatting function that preserves AI structure
                            editor.innerHTML = formatContentPreserving(data.output, formData);
                            updateCounts(); // Update word and character count

                            // Show success message with word count
                            if (typeof toastr !== 'undefined') {
                                toastr.success(`Content generated successfully! (${data.word_count || 0} words)`);
                            }
                        } else {
                            console.error('Editor element not found');
                        }
                    } else {
                        alert(data.message || 'Failed to generate content.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while generating content: ' + error.message);
                })
                .finally(() => {
                    // Re-enable button
                    if (generateBtn) {
                        generateBtn.disabled = false;
                        generateBtn.innerHTML = 'Generate';
                    }
                });
        });

        // Function to update word and character count
        function updateCounts() {
            const editor = document.getElementById('editor-v1');
            if (editor) {
                const content = editor.textContent || editor.innerText;
                const words = content.trim() === '' ? 0 : content.trim().split(/\s+/).length;
                const characters = content.trim().length; // Use trimmed text length
                document.getElementById('word-count').textContent = words;
                document.getElementById('char-count').textContent = characters;
            }
        }

        // HTML escape function to prevent XSS
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // Improved function to format content while preserving AI structure
        function formatContentPreserving(output, formData) {
            // Extract title from first non-system field (works with ANY field name)
            let title = '';
            for (let [key, value] of formData.entries()) {
                // Skip system fields
                if (key !== '_token' && key !== 'language' && key !== 'ai_model' && key !== 'result_length') {
                    title = value;
                    break;
                }
            }

            // Fallback title
            if (!title) {
                title = '{{ $template->title ?? "Generated Content" }}';
            }

            // Process the AI output - preserve markdown-style formatting
            let html = '';
            const lines = output.split('\n');

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i].trim();

                // Empty lines = spacing
                if (line === '') {
                    html += '<br>';
                    continue;
                }

                // Escape HTML to prevent XSS
                const escapedLine = escapeHtml(line);

                // Check for markdown-style headings
                if (line.startsWith('### ')) {
                    html += `<h4>${escapeHtml(line.substring(4))}</h4>`;
                } else if (line.startsWith('## ')) {
                    html += `<h3>${escapeHtml(line.substring(3))}</h3>`;
                } else if (line.startsWith('# ')) {
                    html += `<h2>${escapeHtml(line.substring(2))}</h2>`;
                }
                // Bullet lists
                else if (line.match(/^[*\-]\s+/)) {
                    if (i === 0 || !lines[i-1].trim().match(/^[*\-]\s+/)) {
                        html += '<ul>';
                    }
                    html += `<li>${escapeHtml(line.substring(2))}</li>`;
                    if (i === lines.length - 1 || !lines[i+1].trim().match(/^[*\-]\s+/)) {
                        html += '</ul>';
                    }
                }
                // Numbered lists
                else if (line.match(/^\d+\.\s+/)) {
                    const match = line.match(/^(\d+)\.\s+(.+)$/);
                    if (match) {
                        if (i === 0 || !lines[i-1].trim().match(/^\d+\.\s+/)) {
                            html += '<ol>';
                        }
                        html += `<li>${escapeHtml(match[2])}</li>`;
                        if (i === lines.length - 1 || !lines[i+1].trim().match(/^\d+\.\s+/)) {
                            html += '</ol>';
                        }
                    }
                }
                // Bold text **text**
                else if (line.includes('**')) {
                    let formatted = escapedLine.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    html += `<p>${formatted}</p>`;
                }
                // Regular paragraph
                else {
                    html += `<p>${escapedLine}</p>`;
                }
            }

            return html;
        }

        // Function to create and trigger a file download
        function downloadFile(content, fileName, mimeType) {
            const blob = new Blob([content], { type: mimeType });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Handle export dropdown options
        document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const editor = document.getElementById('editor-v1');
                if (!editor || !editor.innerHTML.trim()) {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning('No content to export. Please generate content first.');
                    } else {
                        alert('No content to export. Please generate content first.');
                    }
                    return;
                }

                const action = this.id;
                const templateTitle = document.querySelector('.nk-editor-title h4').textContent.trim() || 'Generated_Content';
                const timestamp = new Date().toISOString().slice(0, 10).replace(/-/g, '');
                const fileNameBase = `${templateTitle.replace(/\s+/g, '_')}_${timestamp}`;

                if (action === 'copy-text') {
                    // Copy Text to clipboard
                    const content = editor.textContent || editor.innerText;
                    navigator.clipboard.writeText(content).then(() => {
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Text copied to clipboard!');
                        } else {
                            alert('Text copied to clipboard!');
                        }
                    }).catch(err => {
                        console.error('Copy failed:', err);
                        alert('Failed to copy text: ' + err);
                    });
                } else if (action === 'export-text') {
                    // Export as Text File (.txt)
                    const content = editor.textContent || editor.innerText;
                    downloadFile(content, `${fileNameBase}.txt`, 'text/plain');
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Text file downloaded successfully!');
                    }
                } else if (action === 'export-html') {
                    // Export as HTML File (.html)
                    const htmlContent = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${templateTitle}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 40px auto; padding: 20px; }
        h1, h2, h3, h4 { color: #333; margin-top: 20px; }
        p { margin: 10px 0; }
        ul, ol { margin: 10px 0; padding-left: 30px; }
        strong { font-weight: bold; }
    </style>
</head>
<body>
    <h1>${templateTitle}</h1>
    ${editor.innerHTML}
</body>
</html>`;
                    downloadFile(htmlContent, `${fileNameBase}.html`, 'text/html');
                    if (typeof toastr !== 'undefined') {
                        toastr.success('HTML file downloaded successfully!');
                    }
                }
            });
        });

        // Handle Save button functionality
        const saveBtn = document.getElementById('save-content');
        if (saveBtn) {
            saveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const editor = document.getElementById('editor-v1');

                if (!editor || !editor.innerHTML.trim()) {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning('No content to save. Please generate content first.');
                    } else {
                        alert('No content to save. Please generate content first.');
                    }
                    return;
                }

                // Disable save button and show loading state
                saveBtn.disabled = true;
                const originalHTML = saveBtn.innerHTML;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

                const content = editor.innerHTML;
                const textContent = editor.textContent || editor.innerText;
                const wordCount = textContent.trim().split(/\s+/).length;

                // Save to browser's localStorage as backup
                const saveData = {
                    template_id: {{ $template->id }},
                    template_title: '{{ $template->title }}',
                    content: content,
                    word_count: wordCount,
                    saved_at: new Date().toISOString()
                };

                try {
                    localStorage.setItem('saved_content_{{ $template->id }}', JSON.stringify(saveData));

                    if (typeof toastr !== 'undefined') {
                        toastr.success(`Content saved successfully! (${wordCount} words)`);
                    } else {
                        alert(`Content saved successfully! (${wordCount} words)`);
                    }

                    console.log('Content saved to localStorage:', saveData);
                } catch (error) {
                    console.error('Save failed:', error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Failed to save content: ' + error.message);
                    } else {
                        alert('Failed to save content: ' + error.message);
                    }
                } finally {
                    // Re-enable button
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalHTML;
                }
            });
        }

    </script>

@endsection
