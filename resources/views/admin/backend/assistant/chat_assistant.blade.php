@extends('admin.dashboard')
@section('admin')

    <div class="nk-content-body">
        <div class="nk-block-head nk-page-head">
            <div class="nk-block-head-content">
                <h2 class="title display-6">Chat with <strong>{{ $assistant->name }}</strong></h2>
                <p class="text-muted">Role : {{ $assistant->role_description }}</p>
            </div>
        </div>

        <div class="row g-4">

            <!-- ------------------ SIDEBAR / Conversations List ------------------ -->
            <div class="col-md-3">
                <div class="card shadow-sm h-100">

                    <!-- Header -->
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold">Conversations</h5>
                    </div>

                    <div class="card-body p-0">

                        <!-- New Conversation Button -->
                        <a href="{{ route('chat-assistants.new',['assistantId' => $assistant->id]) }}" class="btn btn-new-conversation w-100 mb-3">
                            + New Conversation
                        </a>

                        <!-- Conversation List -->
                        <div class="list-group list-group-flush">
                            @foreach ($conversations as $conv)
                                <a href="{{ route('chat-assistants.select',['assistantId' => $assistant->id, 'conversationId' => $conv->conversation_id ?? $conv->id ]) }}"
                                   class="list-group-item list-group-item-action {{ $selectedConversation && ($selectedConversation->conversation_id ?? $selectedConversation->id) == ($conv->conversation_id ?? $conv->id) ? 'active' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Conversation</h6>
                                        <small>{{ $conv->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 text-truncate">{{ $conv->message ? substr($conv->message, 0 ,20). '...' : 'No messages Yet' }}</p>
                                    <small>{{ $conv->messages_count }} messages</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ------------------ MAIN CHAT AREA ------------------ -->
            <div class="col-md-9">
                <div class="card modern-chat shadow-lg border-0 h-100">

                    <!-- Header -->
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            {{ $selectedConversation ? $assistant->name : 'Select a Conversation' }}
                        </h5>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-0 d-flex flex-column">

                        <!-- Chat Messages -->
                        <div class="chat-area flex-grow-1 p-4" id="chatScroll">
                            @if ($selectedConversation)
                                @foreach ($messages as $msg)
                                    @if ($msg->user_id == Auth::id())
                                        <!-- Right Side (User) -->
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="chat-bubble user-bubble">
                                                <div class="fw-semibold small text-end">You</div>
                                                <div>{{ $msg->message }}</div>
                                                @if ($msg->response)
                                                    <div class="text-muted small mt-1"><em>{{ $msg->response }}</em></div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <!-- Left Side (Assistant) -->
                                        <div class="d-flex justify-content-start mb-3">
                                            <div class="chat-bubble assistant-bubble">
                                                <div class="fw-semibold small">{{ $assistant->name }}</div>
                                                <div>{{ $msg->message }}</div>
                                                @if ($msg->response)
                                                    <div class="text-muted small mt-1"><em>{{ $msg->response }}</em></div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-chat-dots fs-1 d-block mb-3 opacity-50"></i>
                                    <p class="mt-3">Select or start a conversation to begin chatting.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Footer Input -->
                        <div class="chat-input border-top bg-white p-3">
                            <form action="{{ route('chat-assistants.send',['assistantId' => $assistant->id]) }}" method="post" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="message" class="form-control modern-input" placeholder="Type your message..." required>
                                <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                    Send
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ------------------ STYLES ------------------ -->
    <style>
        /* Buttons & Sidebar */
        .btn-new-conversation,
        .list-group-item.active,
        .list-group-item:hover,
        .user-bubble {
            background: linear-gradient(135deg, #c6e2fd, #d4f9ff, rgba(255, 204, 196, 0.91)) !important;
            color: #1a1a1a !important;
            box-shadow: 0 4px 12px rgba(113, 92, 247, 0.25);
            transition: 0.3s;
        }

        .btn-new-conversation {
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
        }

        .btn-new-conversation:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(113, 92, 247, 0.15);
        }

        /* Modern Chat */
        .modern-chat { border-radius: 18px; overflow: hidden; background: #ffffff; }
        .chat-area { background: linear-gradient(135deg, #c6e2fd, #d4f9ff, rgba(255, 204, 196, 0.91)); height: 500px; overflow-y: auto; padding: 12px; color: #1a1a1a; }
        .chat-bubble { max-width: 70%; padding: 12px 16px; border-radius: 18px; line-height: 1.5; font-size: 15px; transition: 0.3s; color: #1a1a1a; }
        .assistant-bubble { background: rgba(255,255,255,0.9); border: 1px solid #d4e6f8; color: #1a1a1a; border-bottom-left-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); transition: 0.3s; }
        .assistant-bubble:hover { background: rgba(255,255,255,0.95); box-shadow: 0 4px 12px rgba(113,92,247,0.15); }
        .modern-input { border-radius: 40px; padding-left: 18px; border: 1px solid #cdd4f7; background: #ffffff; color: #1a1a1a; }
        .modern-input:focus { border-color: #5c6df7; box-shadow: 0 0 0 0.12rem rgba(92,109,247,0.25); }
        .list-group-item { border: none !important; padding: 14px 18px; border-radius: 14px; margin: 6px 0; transition: 0.3s; background: transparent; color: #1a1a1a; font-weight: 500; }
    </style>

    <!-- ------------------ SCROLL SCRIPT ------------------ -->
    <script>
        function scrollToBottom() {
            const chatArea = document.getElementById('chatScroll');
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        window.addEventListener('load', scrollToBottom);

        const chatForm = document.querySelector('.chat-input form');
        if(chatForm){
            chatForm.addEventListener('submit', function() {
                setTimeout(scrollToBottom, 100);
            });
        }
    </script>

@endsection
