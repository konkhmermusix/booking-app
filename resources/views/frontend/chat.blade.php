@extends('layouts.app')
@section('title', 'ជជែកផ្ទាល់ជាមួយសណ្ឋាគារ')

@section('content')
<div class="max-w-5xl mx-auto px-2 sm:px-4 py-2">
    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800/80 overflow-hidden flex flex-col h-[calc(100vh-140px)] min-h-[500px] max-h-[750px]">

        {{-- Chat Header --}}
        <div class="p-3 md:p-3.5 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 flex items-center justify-between shadow-xs shrink-0 sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-blue-500/20">
                        PNT
                    </div>
                    <span class="w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full absolute -bottom-0.5 -right-0.5 shadow-xs"></span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                        <span>សេវាកម្មអតិថិជន</span>
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" id="sound-toggle-btn" onclick="toggleAudioNotification()" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-xs flex items-center gap-1.5" title="បើក/បិទ សំឡេងជូនដំណឹង">
                    <i class="fa-solid fa-volume-high text-blue-500" id="sound-icon"></i>
                    <span class="hidden sm:inline font-semibold">សំឡេង</span>
                </button>
                <a href="{{ route('home') }}" class="text-xs font-semibold px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="fa-solid fa-xmark mr-1"></i> បិទ
                </a>
            </div>
        </div>

        {{-- Messages Container --}}
        <div id="chat-box" class="flex-1 min-h-0 p-3 md:p-5 overflow-y-auto space-y-3 bg-slate-50/60 dark:bg-gray-950/50 scroll-smooth">
            @forelse($messages as $msg)
            @php $isMe = $msg->user_id == Auth::id(); @endphp
            <div class="w-full flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div data-msg-id="{{ $msg->id }}" class="group relative flex gap-2.5 items-end max-w-[85%] md:max-w-[75%] {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                    @if(!$isMe)
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mb-1 shadow-sm" title="សេវាកម្មអតិថិជន">
                        PNT
                    </div>
                    @endif

                    <div class="space-y-1 min-w-0">
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 px-1 {{ $isMe ? 'text-right' : 'text-left' }}">
                            {{ $isMe ? 'អ្នក' : 'សេវាកម្មអតិថិជន' }}
                        </p>
                        <div class="p-3.5 rounded-2xl text-sm shadow-xs {{ $isMe ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-br-xs' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 rounded-bl-xs border border-gray-100 dark:border-gray-700/80' }}">
                            @if($msg->message)
                            <p class="leading-relaxed whitespace-pre-line break-words">{{ $msg->message }}</p>
                            @endif

                            @if($msg->images)
                            <div class="mt-2 rounded-xl overflow-hidden border border-white/20">
                                <a href="{{ asset('storage/'.$msg->images) }}" class="spotlight block cursor-pointer" title="ចុចដើម្បីមើលរូបភាពធំ">
                                    <img src="{{ asset('storage/'.$msg->images) }}" class="max-h-64 w-auto rounded-xl object-cover hover:scale-105 transition-transform duration-300" onload="scrollToBottom()">
                                </a>
                            </div>
                            @endif

                            @if($msg->file_path)
                            <div class="mt-2 pt-2 border-t {{ $isMe ? 'border-white/20' : 'border-gray-200 dark:border-gray-700' }}">
                                <a href="{{ asset('storage/'.$msg->file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold underline hover:opacity-80">
                                    <i class="fa-solid fa-file-arrow-down text-base"></i> ទាញយកឯកសារភ្ជាប់
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 px-1 {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">
                                {{ $msg->created_at ? $msg->created_at->format('h:i A') : '' }}
                            </p>
                            @if($isMe)
                            @if($msg->is_read)
                            <span class="text-blue-500 text-[11px]" title="បានអានរួច (Read)"><i class="fa-solid fa-check-double"></i></span>
                            @else
                            <span class="text-gray-400 text-[11px]" title="បានផ្ញើ (Sent)"><i class="fa-solid fa-check"></i></span>
                            @endif
                            @endif
                        </div>
                    </div>

                    <!-- Action buttons for user -->
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 pb-4">
                        @if($msg->message && $isMe)
                        <button type="button" onclick="editUserMessage({{ $msg->id }}, `{{ addslashes($msg->message) }}`)" class="w-7 h-7 rounded-lg bg-white dark:bg-gray-800 text-gray-500 hover:text-blue-600 border border-gray-100 dark:border-gray-700 shadow-xs flex items-center justify-center text-xs transition cursor-pointer" title="កែសម្រួល">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        @endif
                        @if($isMe)
                        <button type="button" onclick="deleteUserMessage({{ $msg->id }})" class="w-7 h-7 rounded-lg bg-white dark:bg-gray-800 text-gray-500 hover:text-red-600 border border-gray-100 dark:border-gray-700 shadow-xs flex items-center justify-center text-xs transition cursor-pointer" title="លុបសារ">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="h-full flex flex-col items-center justify-center text-center text-gray-400 space-y-3 p-6">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center text-3xl shadow-inner">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">សូមស្វាគមន៍មកកាន់សេវាកម្មជជែកផ្ទាល់!</h3>
                <p class="text-xs text-gray-500 max-w-sm">លោកអ្នកអាចផ្ញើសារសាកសួរព័ត៌មានបន្ទប់ ការកក់ ឬសេវាកម្មផ្សេងៗបានគ្រប់ពេល។</p>
            </div>
            @endforelse
        </div>

        {{-- Quick Inquiry Chips & Input Form --}}
        <div class="p-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-t border-gray-100 dark:border-gray-800 shrink-0 space-y-3 sticky bottom-0 z-20 shadow-lg">

            {{-- Quick Inquiry Suggestion Chips --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs no-scrollbar">
                <span class="text-[11px] text-gray-400 font-semibold shrink-0">សំណួររហ័ស:</span>
                <button type="button" onclick="insertUserSuggestion('ជម្រាបសួរ! ខ្ញុំចង់សាកសួរព័ត៌មានបន្ទប់ស្នាក់នៅ')" class="shrink-0 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-600 hover:text-white text-blue-600 dark:text-blue-400 text-xs font-medium border border-blue-200 dark:border-blue-800/50 transition cursor-pointer">
                    សាកសួរតម្លៃបន្ទប់
                </button>
                <button type="button" onclick="insertUserSuggestion('តើថ្ងៃនេះមានបន្ទប់ទំនេរសម្រាប់កក់ដែរឬទេ?')" class="shrink-0 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-600 hover:text-white text-blue-600 dark:text-blue-400 text-xs font-medium border border-blue-200 dark:border-blue-800/50 transition cursor-pointer">
                    ពិនិត្យបន្ទប់ទំនេរ
                </button>
                <button type="button" onclick="insertUserSuggestion('ខ្ញុំសុំសួរអំពីទីតាំង និងសេវាកម្មបន្ថែម')" class="shrink-0 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-600 hover:text-white text-blue-600 dark:text-blue-400 text-xs font-medium border border-blue-200 dark:border-blue-800/50 transition cursor-pointer">
                    ទីតាំង និងសេវាកម្ម
                </button>
                <button type="button" onclick="insertUserSuggestion('ខ្ញុំត្រូវការជំនួយសម្រាប់ការកក់បន្ទប់')" class="shrink-0 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-600 hover:text-white text-blue-600 dark:text-blue-400 text-xs font-medium border border-blue-200 dark:border-blue-800/50 transition cursor-pointer">
                    ជួយណែនាំការកក់
                </button>
            </div>

            {{-- Live Thumbnail Media Attachment Preview Container --}}
            <div id="media-preview-container" class="hidden p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div id="image-thumb-box" class="w-12 h-12 rounded-xl bg-gray-200 dark:bg-gray-700 overflow-hidden hidden shrink-0">
                        <img id="image-thumb-src" class="w-full h-full object-cover">
                    </div>
                    <div id="file-icon-box" class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 flex items-center justify-center text-lg hidden shrink-0">
                        <i class="fa-solid fa-file"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p id="media-preview-name" class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate"></p>
                        <p id="media-preview-size" class="text-[10px] text-gray-400"></p>
                    </div>
                </div>
                <button type="button" onclick="clearMediaAttachments()" class="w-8 h-8 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Form Input Container --}}
            <form id="chat-form" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">

                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 shrink-0">
                        <label class="w-11 h-11 rounded-2xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center cursor-pointer transition shadow-xs" title="ផ្ញើរូបភាព">
                            <i class="fa-solid fa-image text-sm"></i>
                            <input type="file" name="image" accept="image/*" class="hidden" id="image-input" onchange="handleImageSelected(this)">
                        </label>

                        <label class="w-11 h-11 rounded-2xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center cursor-pointer transition shadow-xs" title="ផ្ញើឯកសារ">
                            <i class="fa-solid fa-paperclip text-sm"></i>
                            <input type="file" name="file" accept=".pdf,.docx,.xlsx,.txt" class="hidden" id="file-input" onchange="handleFileSelected(this)">
                        </label>
                    </div>

                    <div class="flex-1 relative flex items-center">
                        <textarea name="message" id="message-input" rows="1" placeholder="សារ..." required
                            class="w-full bg-gray-50 dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 ring-blue-500/20 dark:text-white transition resize-none max-h-32 leading-normal h-[44px]"
                            onkeydown="handleKeyDown(event)" oninput="autoGrowTextarea(this)"></textarea>
                    </div>

                    <button type="submit" id="send-btn"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 rounded-2xl text-sm font-bold shadow-lg shadow-blue-500/25 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer shrink-0 h-11">
                        <span>ផ្ញើ</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Custom Edit Message Modal -->
<div id="custom-edit-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md shadow-2xl border border-gray-100 dark:border-gray-800 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                <span>កែសម្រួលសារ</span>
            </h3>
            <button onclick="closeEditModal()" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div>
            <textarea id="modal-edit-text" rows="3" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 text-sm outline-none focus:border-blue-500 dark:text-white transition"></textarea>
            <input type="hidden" id="modal-edit-msg-id">
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-500 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition cursor-pointer">
                បោះបង់
            </button>
            <button type="button" onclick="submitEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition cursor-pointer">
                រក្សាទុក
            </button>
        </div>
    </div>
</div>

<!-- Custom Delete Message Modal -->
<div id="custom-delete-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-sm shadow-2xl border border-gray-100 dark:border-gray-800 text-center space-y-4">
        <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/50 text-red-500 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-gray-900 dark:text-white">បញ្ជាក់ការលុបសារ</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">តើអ្នកប្រាកដជាចង់លុបសារនេះមែនទេ?</p>
        </div>
        <input type="hidden" id="modal-delete-msg-id">
        <div class="grid grid-cols-2 gap-2.5 pt-2">
            <button type="button" onclick="closeDeleteModal()" class="py-2.5 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition cursor-pointer">
                បោះបង់
            </button>
            <button type="button" onclick="submitDeleteModal()" class="py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 shadow-md transition cursor-pointer">
                លុបចេញ
            </button>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('chat-form');
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message-input');
    const myUserId = {{ Auth::id() ?? 0 }};
    const storageUrl = "{{ asset('storage') }}";

    let audioNotificationEnabled = true;

    function toggleAudioNotification() {
        audioNotificationEnabled = !audioNotificationEnabled;
        const icon = document.getElementById('sound-icon');
        if (audioNotificationEnabled) {
            icon.className = 'fa-solid fa-volume-high text-blue-500';
        } else {
            icon.className = 'fa-solid fa-volume-xmark text-gray-400';
        }
    }

    function playMessageChime() {
        if (!audioNotificationEnabled) return;
        try {
            const ctx = new(window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch (e) {
            console.log("Audio play error", e);
        }
    }

    // អនុគមន៍ Scroll ថ្មីដែលរង់ចាំ DOM Render 100ms
    function scrollToBottom() {
        if (chatBox) {
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        }
    }

    // រង់ចាំឱ្យធាតុទាំងអស់ទាញយក និង Render រួចរាល់ទើប Scroll ពេលបើកដំបូង
    document.addEventListener('DOMContentLoaded', () => {
        scrollToBottom();
    });

    function autoGrowTextarea(element) {
        element.style.height = 'auto';
        element.style.height = (element.scrollHeight) + 'px';
    }

    function handleKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.requestSubmit();
        }
    }

    function insertUserSuggestion(text) {
        messageInput.value = text;
        autoGrowTextarea(messageInput);
        messageInput.focus();
    }

    function handleImageSelected(input) {
        const fileInput = document.getElementById('file-input');
        fileInput.value = '';
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-thumb-src').src = e.target.result;
                document.getElementById('image-thumb-box').classList.remove('hidden');
                document.getElementById('file-icon-box').classList.add('hidden');
                document.getElementById('media-preview-name').innerText = file.name;
                document.getElementById('media-preview-size').innerText = (file.size / 1024).toFixed(1) + ' KB';
                document.getElementById('media-preview-container').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
            messageInput.removeAttribute('required');
        } else {
            clearMediaAttachments();
        }
    }

    function handleFileSelected(input) {
        const imgInput = document.getElementById('image-input');
        imgInput.value = '';
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('image-thumb-box').classList.add('hidden');
            document.getElementById('file-icon-box').classList.remove('hidden');
            document.getElementById('media-preview-name').innerText = file.name;
            document.getElementById('media-preview-size').innerText = (file.size / 1024).toFixed(1) + ' KB';
            document.getElementById('media-preview-container').classList.remove('hidden');
            messageInput.removeAttribute('required');
        } else {
            clearMediaAttachments();
        }
    }

    function clearMediaAttachments() {
        document.getElementById('image-input').value = '';
        document.getElementById('file-input').value = '';
        document.getElementById('media-preview-container').classList.add('hidden');
        document.getElementById('image-thumb-box').classList.add('hidden');
        document.getElementById('file-icon-box').classList.add('hidden');
        messageInput.setAttribute('required', 'required');
    }

    function buildMessageHtml(msg) {
        const isMe = msg.user_id == myUserId;
        const msgId = msg.id;
        const formattedTime = msg.created_at ? new Date(msg.created_at).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        }) : '';
        const safeMsgText = msg.message ? msg.message.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;") : '';
        const attrMsgText = safeMsgText.replace(/\\/g, "\\\\").replace(/\n/g, "\\n").replace(/`/g, "\\`");

        let mediaHtml = '';
        if (msg.images) {
            mediaHtml += `<div class="mt-2 rounded-xl overflow-hidden border border-white/20">
                <a href="${storageUrl}/${msg.images}" class="spotlight block cursor-pointer" title="ចុចដើម្បីមើលរូបភាពធំ">
                    <img src="${storageUrl}/${msg.images}" class="max-h-64 w-auto rounded-xl object-cover hover:scale-105 transition-transform duration-300" onload="scrollToBottom()">
                </a>
            </div>`;
        }
        if (msg.file_path) {
            mediaHtml += `<div class="mt-2 pt-2 border-t ${isMe ? 'border-white/20' : 'border-gray-200 dark:border-gray-700'}">
                <a href="${storageUrl}/${msg.file_path}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold underline hover:opacity-80">
                    <i class="fa-solid fa-file-arrow-down text-base"></i> ទាញយកឯកសារភ្ជាប់
                </a>
            </div>`;
        }

        let editBtn = (msg.message && isMe) ? `
            <button type="button" onclick="editUserMessage(${msgId}, \`${attrMsgText}\`)" class="w-7 h-7 rounded-lg bg-white dark:bg-gray-800 text-gray-500 hover:text-blue-600 border border-gray-100 dark:border-gray-700 shadow-xs flex items-center justify-center text-xs transition cursor-pointer" title="កែសម្រួល">
                <i class="fa-solid fa-pen"></i>
            </button>` : '';

        let deleteBtn = isMe ? `
            <button type="button" onclick="deleteUserMessage(${msgId})" class="w-7 h-7 rounded-lg bg-white dark:bg-gray-800 text-gray-500 hover:text-red-600 border border-gray-100 dark:border-gray-700 shadow-xs flex items-center justify-center text-xs transition cursor-pointer" title="លុបសារ">
                <i class="fa-solid fa-trash"></i>
            </button>` : '';

        let statusCheck = isMe ? (msg.is_read ? '<span class="text-blue-500 text-[11px]" title="បានអានរួច (Read)"><i class="fa-solid fa-check-double"></i></span>' : '<span class="text-gray-400 text-[11px]" title="បានផ្ញើ (Sent)"><i class="fa-solid fa-check"></i></span>') : '';

        return `
        <div class="w-full flex ${isMe ? 'justify-end' : 'justify-start'}">
            <div data-msg-id="${msgId}" class="group relative flex gap-2.5 items-end max-w-[85%] md:max-w-[75%] ${isMe ? 'flex-row-reverse' : 'flex-row'}">
                ${!isMe ? '<div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mb-1 shadow-sm" title="សេវាកម្មអតិថិជន">PNT</div>' : ''}
                <div class="space-y-1 min-w-0">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 px-1 ${isMe ? 'text-right' : 'text-left'}">
                        ${isMe ? 'អ្នក' : 'សេវាកម្មអតិថិជន PNT'}
                    </p>
                    <div class="p-3.5 rounded-2xl text-sm shadow-xs ${isMe ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-br-xs' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 rounded-bl-xs border border-gray-100 dark:border-gray-700/80'}">
                        ${safeMsgText ? `<p class="leading-relaxed whitespace-pre-line break-words">${safeMsgText}</p>` : ''}
                        ${mediaHtml}
                    </div>
                    <div class="flex items-center gap-1.5 px-1 ${isMe ? 'justify-end' : 'justify-start'}">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">${formattedTime}</p>
                        ${statusCheck}
                    </div>
                </div>
                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 pb-4">
                    ${editBtn}
                    ${deleteBtn}
                </div>
            </div>
        </div>`;
    }

    async function syncMessages() {
        const conversationIdEl = document.querySelector('[name="conversation_id"]');
        if (!conversationIdEl) return;
        try {
            let res = await fetch(`/chat/fetch/${conversationIdEl.value}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            let messages = await res.json();
            if (Array.isArray(messages)) {
                let hasNewPartnerMessage = false;
                let hasAnyNewMessage = false;

                messages.forEach(msg => {
                    let existing = chatBox.querySelector(`[data-msg-id="${msg.id}"]`);
                    if (!existing) {
                        chatBox.insertAdjacentHTML('beforeend', buildMessageHtml(msg));
                        hasAnyNewMessage = true;
                        if (msg.user_id != myUserId) {
                            hasNewPartnerMessage = true;
                        }
                    } else {
                        if (msg.user_id == myUserId && msg.is_read) {
                            let checkEl = existing.querySelector('.fa-check');
                            if (checkEl) {
                                checkEl.parentElement.outerHTML = '<span class="text-blue-500 text-[11px]" title="បានអានរួច (Read)"><i class="fa-solid fa-check-double"></i></span>';
                            }
                        }
                    }
                });

                if (hasNewPartnerMessage) {
                    playMessageChime();
                }

                if (hasAnyNewMessage) {
                    scrollToBottom();
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    // Auto-poll រៀងរាល់ 2.5 វិនាទី
    setInterval(syncMessages, 2500);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn = document.getElementById('send-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i>';

        try {
            let res = await fetch("{{ route('chat.send') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });

            let data = await res.json();

            if (data.success) {
                form.reset();
                messageInput.style.height = 'auto';
                clearMediaAttachments();
                await syncMessages();
                scrollToBottom();
            }
        } catch (err) {
            console.error(err);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span>ផ្ញើ</span> <i class="fa-solid fa-paper-plane text-xs"></i>';
        }
    });

    function editUserMessage(msgId, oldText) {
        document.getElementById('modal-edit-msg-id').value = msgId;
        document.getElementById('modal-edit-text').value = oldText;
        document.getElementById('custom-edit-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('custom-edit-modal').classList.add('hidden');
    }

    async function submitEditModal() {
        const msgId = document.getElementById('modal-edit-msg-id').value;
        const newText = document.getElementById('modal-edit-text').value;
        if (!newText || !newText.trim()) return;

        try {
            let res = await fetch(`/chat/messages/${msgId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: newText
                })
            });
            let data = await res.json();
            if (data.success) {
                closeEditModal();
                let el = chatBox.querySelector(`[data-msg-id="${msgId}"] p`);
                if (el) el.innerText = newText;
                else location.reload();
            }
        } catch (e) {
            console.error(e);
        }
    }

    function deleteUserMessage(msgId) {
        document.getElementById('modal-delete-msg-id').value = msgId;
        document.getElementById('custom-delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('custom-delete-modal').classList.add('hidden');
    }

    async function submitDeleteModal() {
        const msgId = document.getElementById('modal-delete-msg-id').value;
        if (!msgId) return;

        try {
            let res = await fetch(`/chat/messages/${msgId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            let data = await res.json();
            if (data.success) {
                closeDeleteModal();
                let el = chatBox.querySelector(`[data-msg-id="${msgId}"]`);
                if (el) el.remove();
            }
        } catch (e) {
            console.error(e);
        }
    }
</script>
@endsection