@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងសារជជែក')

@section('content')
<div class="bg-white dark:bg-gray-900 rounded-2xl md:rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800/80 overflow-hidden grid grid-cols-1 md:grid-cols-12 xl:grid-cols-4 h-[calc(100vh-140px)] md:h-[calc(100vh-120px)] min-h-[500px]">

    <!-- LEFT: CONVERSATION LIST (4 COLS ON TABLET, 1 COL ON DESKTOP) -->
    <div class="{{ $activeConversation ? 'hidden md:flex' : 'flex' }} md:col-span-4 xl:col-span-1 border-r border-gray-100 dark:border-gray-800 bg-gray-50/60 dark:bg-gray-900/60 flex-col h-full min-h-0">

        {{-- Search & Header --}}
        <div class="p-4 border-b border-gray-100 dark:border-gray-800/80 shrink-0 space-y-3 bg-white/50 dark:bg-gray-900/50 backdrop-blur-md">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-gray-900 dark:text-white text-sm flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-comments"></i>
                    </span>
                    <span>សារជជែក</span>
                </h2>
                @php
                    $totalUnread = $conversations->sum('unread_count');
                @endphp
                <div class="flex items-center gap-1.5">
                    @if($totalUnread > 0)
                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-red-500 text-white animate-pulse">
                        {{ $totalUnread }} ថ្មី
                    </span>
                    @endif
                    <span class="text-[11px] font-bold px-3 py-0.5 rounded-full bg-blue-50 dark:bg-blue-950/80 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50">
                        {{ count($conversations) }}
                    </span>
                </div>
            </div>

            {{-- Filter Tabs (All / Unread) --}}
            <div class="grid grid-cols-2 gap-1 p-2 bg-gray-200/60 dark:bg-gray-800 rounded-xl text-xs">
                <button type="button" onclick="setConvFilter('all')" id="btn-filter-all" class="py-2 px-2 rounded-lg font-bold transition text-center bg-white dark:bg-gray-700 text-blue-600 dark:text-white shadow-xs">
                    ទាំងអស់ ({{ count($conversations) }})
                </button>
                <button type="button" onclick="setConvFilter('unread')" id="btn-filter-unread" class="py-2 px-2 rounded-lg font-bold transition text-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    មិនទាន់អាន ({{ $totalUnread }})
                </button>
            </div>

            {{-- Quick Search Input --}}
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" id="conv-search-input" onkeyup="filterConversations()" placeholder="ស្វែងរកឈ្មោះអតិថិជន..."
                    class="w-full bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700/80 rounded-xl pl-8 pr-3 py-3 text-xs text-gray-800 dark:text-gray-200 outline-none focus:border-blue-500 focus:ring-2 ring-blue-500/20 transition placeholder-gray-400">
            </div>
        </div>

        {{-- Conversation List Scroll --}}
        <div id="conv-list-container" class="flex-1 min-h-0 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800/50">
            @forelse($conversations as $conv)
            @php
            $partner = $conv->sender_id == auth()->id() ? $conv->receiver : $conv->sender;
            $partnerName = $partner->name ?? 'អតិថិជន';
            $lastMessage = $conv->messages->last();
            $isActive = $activeConversation && $activeConversation->id == $conv->id;
            $hasUnread = $conv->unread_count > 0;
            @endphp

            <a href="{{ route('messages.index', ['conversation_id' => $conv->id]) }}"
                data-name="{{ strtolower($partnerName) }}"
                data-unread="{{ $hasUnread ? 'true' : 'false' }}"
                class="conv-item flex items-center gap-3 p-3.5 transition-all duration-200 block hover:bg-gray-100/70 dark:hover:bg-gray-800/70 relative group {{ $isActive ? 'bg-gradient-to-r from-blue-50/90 to-indigo-50/40 dark:from-blue-950/50 dark:to-indigo-950/20 border-l-4 border-blue-600 shadow-sm' : '' }}">

                <div class="relative shrink-0">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center shadow-md shadow-blue-500/20 text-sm overflow-hidden">
                        @if($partner && $partner->avatar)
                        <img src="{{ asset('storage/'.$partner->avatar) }}" class="w-full h-full object-cover">
                        @else
                        {{ mb_substr($partnerName, 0, 1, 'utf-8') }}
                        @endif
                    </div>
                    <span class="w-3 h-3 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full absolute -bottom-0.5 -right-0.5 shadow-xs"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-1">
                        <h3 class="text-xs font-bold truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition {{ $hasUnread ? 'text-blue-600 dark:text-blue-400 font-extrabold' : 'text-gray-900 dark:text-white' }}">
                            {{ $partnerName }}
                        </h3>
                        @if($lastMessage && $lastMessage->created_at)
                        <span class="text-[10px] text-gray-400 font-medium">
                            {{ $lastMessage->created_at->format('h:i A') }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-1">
                        <p class="text-xs truncate flex items-center gap-1 {{ $hasUnread ? 'text-gray-900 dark:text-gray-100 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                            @if($lastMessage && $lastMessage->images)
                            <i class="fa-solid fa-image text-blue-500 text-[10px]"></i> <span>រូបភាព</span>
                            @elseif($lastMessage && $lastMessage->file_path)
                            <i class="fa-solid fa-paperclip text-blue-500 text-[10px]"></i> <span>ឯកសារ</span>
                            @else
                            <span>{{ $lastMessage->message ?? 'គ្មានសារនៅឡើយ' }}</span>
                            @endif
                        </p>
                        @if($hasUnread)
                        <span class="shrink-0 w-5 h-5 rounded-full bg-red-500 text-white font-black text-[10px] flex items-center justify-center shadow-xs">
                            {{ $conv->unread_count }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="p-8 text-center text-gray-400 text-xs">
                <i class="fa-solid fa-inbox text-2xl mb-2 block opacity-40"></i>
                មិនទាន់មានសារជជែកនៅឡើយទេ
            </div>
            @endforelse
        </div>
    </div>

    <!-- MIDDLE: CHAT MESSAGES BOX (8 COLS ON TABLET, 2 COLS ON DESKTOP) -->
    <div class="{{ $activeConversation ? 'flex' : 'hidden md:flex' }} md:col-span-8 xl:col-span-2 flex-col h-full min-h-0 bg-white dark:bg-gray-900">

        @if($activeConversation)
        @php
        $activePartner = $activeConversation->sender_id == auth()->id() ? $activeConversation->receiver : $activeConversation->sender;
        $activePartnerName = $activePartner->name ?? 'អតិថិជន';
        @endphp

        <!-- ACTIVE CHAT HEADER -->
        <div class="p-4 border-b border-gray-100 dark:border-gray-800/80 flex items-center justify-between gap-3 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md shrink-0 shadow-xs sticky top-0 z-20">
            <div class="flex items-center gap-3">
                {{-- Mobile Back Button --}}
                <a href="{{ route('messages.index') }}" class="md:hidden text-gray-500 hover:text-blue-600 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition mr-1" title="ត្រឡប់ក្រោយ">
                    <i class="fa-solid fa-arrow-left text-base"></i>
                </a>

                <div class="relative shrink-0">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center shadow-md shadow-blue-500/20 text-sm overflow-hidden">
                        @if($activePartner && $activePartner->avatar)
                        <img src="{{ asset('storage/'.$activePartner->avatar) }}" class="w-full h-full object-cover">
                        @else
                        {{ mb_substr($activePartnerName, 0, 1, 'utf-8') }}
                        @endif
                    </div>
                    <span class="w-3 h-3 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full absolute -bottom-0.5 -right-0.5"></span>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>{{ $activePartnerName }}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/80 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 uppercase tracking-wider">
                            {{ $activePartner->role ?? 'customer' }}
                        </span>
                    </h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center gap-2 mt-0.5">
                        <span class="flex items-center gap-1"><i class="fa-solid fa-envelope text-blue-500 text-[10px]"></i> {{ $activePartner->email ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" id="admin-sound-toggle-btn" onclick="toggleAdminAudioNotification()" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-xs flex items-center gap-1.5" title="បើក/បិទ សំឡេង">
                    <i class="fa-solid fa-volume-high text-blue-500" id="admin-sound-icon"></i>
                </button>
                <button type="button" onclick="confirmAdminDeleteConversation({{ $activeConversation->id }})" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white dark:bg-red-950/40 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white text-xs font-bold transition-all shadow-xs flex items-center cursor-pointer">
                    <i class="fa-solid fa-trash-can"></i>
                    <span class="hidden sm:inline"></span>
                </button>
            </div>
        </div>

        <!-- CHAT MESSAGES BODY -->
        <div id="admin-chat-box" class="flex-1 min-h-0 p-5 overflow-y-auto space-y-4 bg-slate-50/50 dark:bg-gray-950/50 scroll-smooth">
            @foreach($chatMessages as $msg)
            @php $isMe = $msg->user_id == auth()->id(); @endphp
            <div class="w-full flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div data-msg-id="{{ $msg->id }}" class="group relative flex gap-3 items-end max-w-[85%] md:max-w-[75%] {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                    @if(!$isMe)
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mb-1 shadow-sm" title="{{ $activePartnerName }}">
                        {{ mb_substr($activePartnerName, 0, 1, 'utf-8') }}
                    </div>
                    @endif

                    <div class="space-y-1 min-w-0">
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 px-1 {{ $isMe ? 'text-right' : 'text-left' }}">
                            {{ $isMe ? 'អ្នក (Admin)' : $activePartnerName }}
                        </p>
                        <div class="p-3.5 rounded-2xl text-sm shadow-sm relative group/bubble transition-all {{ $isMe ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-br-xs' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-bl-xs border border-gray-100 dark:border-gray-700/70' }}">
                            @if($msg->message)
                            <p class="leading-relaxed whitespace-pre-line break-words">{{ $msg->message }}</p>
                            @endif

                            @if($msg->images)
                            <div class="mt-2 rounded-xl overflow-hidden border border-white/20">
                                <a href="{{ asset('storage/'.$msg->images) }}" class="spotlight block cursor-pointer" title="ចុចដើម្បីមើលរូបភាពធំ">
                                    <img src="{{ asset('storage/'.$msg->images) }}" class="max-h-64 w-auto rounded-xl object-cover hover:scale-105 transition-transform duration-300" onload="scrollToAdminBottom()">
                                </a>
                            </div>
                            @endif

                            @if($msg->file_path)
                            <div class="mt-2 pt-2 border-t {{ $isMe ? 'border-white/20' : 'border-gray-200 dark:border-gray-700' }}">
                                <a href="{{ asset('storage/'.$msg->file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold underline hover:opacity-80">
                                    <i class="fa-solid fa-file-arrow-down text-base"></i> ទាញយកឯកសារ
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 px-1 {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <p class="text-[10px] text-gray-400 font-medium">
                                {{ $msg->created_at ? $msg->created_at->format('h:i A') : '' }}
                            </p>
                            @if($isMe)
                                @if($msg->is_read)
                                <span class="text-blue-500 text-[11px]" title="អតិថិជនបានអានរួច"><i class="fa-solid fa-check-double"></i></span>
                                @else
                                <span class="text-gray-400 text-[11px]" title="បានផ្ញើ"><i class="fa-solid fa-check"></i></span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Action buttons (Edit / Delete) -->
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center gap-1 pb-4">
                        @if($msg->message && $isMe)
                        <button type="button" onclick="editMessage({{ $msg->id }}, `{{ addslashes($msg->message) }}`)" class="w-7 h-7 rounded-xl bg-white dark:bg-gray-800 text-gray-500 hover:text-blue-600 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center text-xs transition cursor-pointer" title="កែសម្រួល">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        @endif
                        <button type="button" onclick="confirmAdminDeleteMessage({{ $msg->id }})" class="w-7 h-7 rounded-xl bg-white dark:bg-gray-800 text-gray-500 hover:text-red-600 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center text-xs transition cursor-pointer" title="លុបសារ">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- INPUT FORM CONTROL -->
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md shrink-0 space-y-2.5 sticky bottom-0 z-20 shadow-lg">

            {{-- Quick Template Chips --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs no-scrollbar">
                <span class="text-[11px] text-gray-400 font-semibold shrink-0">ចម្លើយរហ័ស:</span>
                <button type="button" onclick="insertTemplate('ជម្រាបសួរ! តើខ្ញុំអាចជួយអ្វីបានដែរ?')" class="shrink-0 px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/50 hover:text-blue-600 text-gray-700 dark:text-gray-300 text-[11px] font-medium transition cursor-pointer border border-gray-200 dark:border-gray-700">
                    ជម្រាបសួរ
                </button>
                <button type="button" onclick="insertTemplate('កំពុងមានបន្ទប់ទំនេរសម្រាប់លោកអ្នក។')" class="shrink-0 px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/50 hover:text-blue-600 text-gray-700 dark:text-gray-300 text-[11px] font-medium transition cursor-pointer border border-gray-200 dark:border-gray-700">
                    មានបន្ទប់ទំនេរ
                </button>
                <button type="button" onclick="insertTemplate('លោកអ្នកអាចធ្វើការកក់ និងទូទាត់ប្រាក់តាមរយៈប្រព័ន្ធវេបសាយផ្ទាល់បានយ៉ាងងាយស្រួល។')" class="shrink-0 px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/50 hover:text-blue-600 text-gray-700 dark:text-gray-300 text-[11px] font-medium transition cursor-pointer border border-gray-200 dark:border-gray-700">
                    ព័ត៌មានទូទាត់
                </button>
                <button type="button" onclick="insertTemplate('ម៉ោងចូលស្នាក់នៅគឺចាប់ពីម៉ោង ២:០០ PM ហើយម៉ោងចាកចេញគឺត្រឹមម៉ោង ១២:០០ PM។')" class="shrink-0 px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/50 hover:text-blue-600 text-gray-700 dark:text-gray-300 text-[11px] font-medium transition cursor-pointer border border-gray-200 dark:border-gray-700">
                    ម៉ោងចូល/ចេញ
                </button>
                <button type="button" onclick="insertTemplate('សូមអរគុណច្រើនសម្រាប់ការទំនាក់ទំនងមកកាន់សណ្ឋាគារយើងខ្ញុំ!')" class="shrink-0 px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950/50 hover:text-blue-600 text-gray-700 dark:text-gray-300 text-[11px] font-medium transition cursor-pointer border border-gray-200 dark:border-gray-700">
                    សូមអរគុណ
                </button>
            </div>

            {{-- Live Attachment Preview Container for Admin --}}
            <div id="admin-media-preview-container" class="hidden p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div id="admin-image-thumb-box" class="w-12 h-12 rounded-xl bg-gray-200 dark:bg-gray-700 overflow-hidden hidden shrink-0">
                        <img id="admin-image-thumb-src" class="w-full h-full object-cover">
                    </div>
                    <div id="admin-file-icon-box" class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 flex items-center justify-center text-lg hidden shrink-0">
                        <i class="fa-solid fa-file"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p id="admin-media-preview-name" class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate"></p>
                        <p id="admin-media-preview-size" class="text-[10px] text-gray-400"></p>
                    </div>
                </div>
                <button type="button" onclick="clearAdminMediaAttachments()" class="w-8 h-8 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 flex items-center justify-center text-xs transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="admin-chat-form" method="POST" action="{{ route('messages.store', $activeConversation->id) }}" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 shrink-0">
                        <label class="w-11 h-11 rounded-2xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center cursor-pointer transition shadow-xs" title="ផ្ញើរូបភាព">
                            <i class="fa-solid fa-image text-sm"></i>
                            <input type="file" name="images" accept="image/*" class="hidden" id="admin-img-input" onchange="handleAdminImageSelected(this)">
                        </label>

                        <label class="w-11 h-11 rounded-2xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center cursor-pointer transition shadow-xs" title="ផ្ញើឯកសារ">
                            <i class="fa-solid fa-paperclip text-sm"></i>
                            <input type="file" name="file" accept=".pdf,.docx,.xlsx,.txt" class="hidden" id="admin-file-input" onchange="handleAdminFileSelected(this)">
                        </label>
                    </div>

                    <div class="flex-1 relative flex items-center">
                        <textarea name="message" id="admin-msg-input" rows="1" placeholder="សារ..." required
                            class="w-full bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700/80 rounded-2xl px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 ring-blue-500/20 dark:text-white transition resize-none max-h-32 leading-normal h-[44px]"
                            onkeydown="handleAdminKeyDown(event)" oninput="autoGrowAdminTextarea(this)"></textarea>
                    </div>

                    <button type="submit" id="admin-send-btn"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 rounded-2xl text-sm font-bold shadow-lg shadow-blue-500/25 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer shrink-0 h-11">
                        <span>ផ្ញើ</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </div>
            </form>
        </div>

        @else
        <div class="flex flex-col items-center justify-center h-full text-gray-400 space-y-3 p-8">
            <div class="w-20 h-20 rounded-3xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center text-4xl shadow-inner">
                <i class="fa-solid fa-comments"></i>
            </div>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">ជ្រើសរើសការជជែក</h3>
            <p class="text-xs text-gray-400 text-center max-w-sm">សូមជ្រើសរើសឈ្មោះអតិថិជនពីបញ្ជីខាងឆ្វេង ដើម្បីមើល និងឆ្លើយតបសារជជែក។</p>
        </div>
        @endif
    </div>

    <!-- RIGHT: CUSTOMER PROFILE DETAILS SIDEBAR (1 COL) -->
    <div class="hidden xl:flex xl:col-span-1 border-l border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 flex-col h-full min-h-0 overflow-y-auto">
        @if($activeConversation && isset($activePartner))
        {{-- Profile Header Card --}}
        <div class="p-6 text-center border-b border-gray-100 dark:border-gray-800/80 space-y-3 bg-white/40 dark:bg-gray-900/40 backdrop-blur-md">
            <div class="relative inline-block">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white text-2xl font-bold flex items-center justify-center mx-auto shadow-xl shadow-blue-500/20 overflow-hidden">
                    @if($activePartner && $activePartner->avatar)
                    <img src="{{ asset('storage/'.$activePartner->avatar) }}" class="w-full h-full object-cover">
                    @else
                    {{ mb_substr($activePartnerName, 0, 1, 'utf-8') }}
                    @endif
                </div>
                <span class="w-4 h-4 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full absolute bottom-1 right-1 shadow-xs" title="សកម្ម (Online)"></span>
            </div>

            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                    {{ $activePartnerName }}
                </h3>
                <span class="inline-block mt-1 px-3 py-0.5 text-[10px] font-bold rounded-full bg-blue-50 dark:bg-blue-950/80 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 uppercase tracking-wider">
                    {{ $activePartner->role ?? 'Customer' }}
                </span>
            </div>
        </div>

        {{-- Contact & Account Info --}}
        <div class="p-5 space-y-5 flex-1 text-xs">
            <div>
                <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5">ព័ត៌មានទំនាក់ទំនង</h4>
                <div class="space-y-2.5 bg-white dark:bg-gray-800/60 p-3.5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-2xs">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] text-gray-400 font-medium">អ៊ីមែល</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 truncate">{{ $activePartner->email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2.5 border-t border-gray-100 dark:border-gray-700/50">
                        <span class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] text-gray-400 font-medium">លេខទូរស័ព្ទ</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 truncate">{{ $activePartner->phone ?? 'មិនទាន់បញ្ចូល' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5">ព័ត៌មានគណនី</h4>
                <div class="space-y-2.5 bg-white dark:bg-gray-800/60 p-3.5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400 font-medium">កាលបរិច្ឆេទចុះឈ្មោះ:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $activePartner->created_at ? $activePartner->created_at->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700/50">
                        <span class="text-gray-500 dark:text-gray-400 font-medium">ស្ថានភាពគណនី:</span>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 font-bold text-[10px]">សកម្ម</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div>
                <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5">សកម្មភាពលឿន</h4>
                <div class="space-y-2">
                    @if($activePartner->email)
                    <a href="mailto:{{ $activePartner->email }}" class="w-full py-2.5 px-3 rounded-xl bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700/80 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 font-bold flex items-center justify-center gap-2 transition shadow-xs">
                        <i class="fa-solid fa-paper-plane text-blue-500"></i>
                        <span>ផ្ញើអ៊ីមែល</span>
                    </a>
                    @endif
                    <button type="button" onclick="confirmAdminDeleteConversation({{ $activeConversation->id }})" class="w-full py-2.5 px-3 rounded-xl bg-red-50 hover:bg-red-100 dark:bg-red-950/40 dark:hover:bg-red-950/80 text-red-600 dark:text-red-400 font-bold flex items-center justify-center gap-2 transition shadow-xs cursor-pointer">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>លុបការជជែក</span>
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-full text-gray-400 space-y-2 p-6 text-center">
            <i class="fa-solid fa-id-card text-3xl opacity-30"></i>
            <p class="text-xs">គ្មានព័ត៌មានអតិថិជនត្រូវបង្ហាញទេ</p>
        </div>
        @endif
    </div>
</div>

<!-- Custom Admin Delete Message Modal -->
<div id="admin-delete-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 w-full max-w-sm shadow-2xl border border-gray-100 dark:border-gray-800 text-center space-y-4">
        <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/50 text-red-500 flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-gray-900 dark:text-white" id="admin-delete-modal-title">បញ្ជាក់ការលុបសារ</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="admin-delete-modal-desc">តើអ្នកប្រាកដជាចង់លុបសារនេះមែនទេ?</p>
        </div>
        <input type="hidden" id="admin-modal-delete-type">
        <input type="hidden" id="admin-modal-delete-id">
        <div class="grid grid-cols-2 gap-2.5 pt-2">
            <button type="button" onclick="closeAdminDeleteModal()" class="py-2.5 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition cursor-pointer">
                បោះបង់
            </button>
            <button type="button" onclick="submitAdminDeleteModal()" class="py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 shadow-md transition cursor-pointer">
                លុបចេញ
            </button>
        </div>
    </div>
</div>

<!-- Custom Admin Edit Message Modal -->
<div id="admin-edit-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 w-full max-w-md shadow-2xl border border-gray-100 dark:border-gray-800 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                <span>កែសម្រួលសារ</span>
            </h3>
            <button onclick="closeAdminEditModal()" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div>
            <textarea id="admin-modal-edit-text" rows="3" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 text-sm outline-none focus:border-blue-500 dark:text-white transition"></textarea>
            <input type="hidden" id="admin-modal-edit-msg-id">
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeAdminEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-500 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition cursor-pointer">
                បោះបង់
            </button>
            <button type="button" onclick="submitAdminEditModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition cursor-pointer">
                រក្សាទុក
            </button>
        </div>
    </div>
</div>

<script>
    const adminUserId = {{ auth()->id() }};
    const adminStorageUrl = "{{ asset('storage') }}";
    const partnerNameDefault = "{{ addslashes($activePartnerName ?? 'អតិថិជន') }}";
    const partnerInitialDefault = "{{ mb_substr($activePartnerName ?? 'អតិថិជន', 0, 1, 'utf-8') }}";

    let adminAudioEnabled = true;
    let currentConvFilter = 'all';

    function toggleAdminAudioNotification() {
        adminAudioEnabled = !adminAudioEnabled;
        const icon = document.getElementById('admin-sound-icon');
        if (adminAudioEnabled) {
            icon.className = 'fa-solid fa-volume-high text-blue-500';
        } else {
            icon.className = 'fa-solid fa-volume-xmark text-gray-400';
        }
    }

    function playAdminChime() {
        if (!adminAudioEnabled) return;
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(523.25, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(783.99, ctx.currentTime + 0.15);
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

    // Scroll ទៅបាតក្រោមដោយស្វ័យប្រវត្តិ
    function scrollToAdminBottom() {
        const box = document.getElementById('admin-chat-box');
        if (box) {
            setTimeout(() => {
                box.scrollTop = box.scrollHeight;
            }, 100);
        }
    }

    function setConvFilter(filter) {
        currentConvFilter = filter;
        const btnAll = document.getElementById('btn-filter-all');
        const btnUnread = document.getElementById('btn-filter-unread');
        if (filter === 'all') {
            btnAll.className = 'py-1 px-2 rounded-lg font-bold transition text-center bg-white dark:bg-gray-700 text-blue-600 dark:text-white shadow-xs';
            btnUnread.className = 'py-1 px-2 rounded-lg font-bold transition text-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white';
        } else {
            btnUnread.className = 'py-1 px-2 rounded-lg font-bold transition text-center bg-white dark:bg-gray-700 text-blue-600 dark:text-white shadow-xs';
            btnAll.className = 'py-1 px-2 rounded-lg font-bold transition text-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white';
        }
        filterConversations();
    }

    function filterConversations() {
        const query = document.getElementById('conv-search-input').value.toLowerCase();
        const items = document.querySelectorAll('.conv-item');
        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const isUnread = item.getAttribute('data-unread') === 'true';
            const matchesQuery = name.includes(query);
            const matchesFilter = (currentConvFilter === 'all') || (currentConvFilter === 'unread' && isUnread);

            if (matchesQuery && matchesFilter) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function insertTemplate(text) {
        const input = document.getElementById('admin-msg-input');
        if (input) {
            input.value = text;
            autoGrowAdminTextarea(input);
            input.focus();
        }
    }

    function autoGrowAdminTextarea(element) {
        element.style.height = 'auto';
        element.style.height = (element.scrollHeight) + 'px';
    }

    function handleAdminKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            const form = document.getElementById('admin-chat-form');
            if (form) form.requestSubmit();
        }
    }

    function handleAdminImageSelected(input) {
        const fileInput = document.getElementById('admin-file-input');
        fileInput.value = '';
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('admin-image-thumb-src').src = e.target.result;
                document.getElementById('admin-image-thumb-box').classList.remove('hidden');
                document.getElementById('admin-file-icon-box').classList.add('hidden');
                document.getElementById('admin-media-preview-name').innerText = file.name;
                document.getElementById('admin-media-preview-size').innerText = (file.size / 1024).toFixed(1) + ' KB';
                document.getElementById('admin-media-preview-container').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
            document.getElementById('admin-msg-input').removeAttribute('required');
        } else {
            clearAdminMediaAttachments();
        }
    }

    function handleAdminFileSelected(input) {
        const imgInput = document.getElementById('admin-img-input');
        imgInput.value = '';
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('admin-image-thumb-box').classList.add('hidden');
            document.getElementById('admin-file-icon-box').classList.remove('hidden');
            document.getElementById('admin-media-preview-name').innerText = file.name;
            document.getElementById('admin-media-preview-size').innerText = (file.size / 1024).toFixed(1) + ' KB';
            document.getElementById('admin-media-preview-container').classList.remove('hidden');
            document.getElementById('admin-msg-input').removeAttribute('required');
        } else {
            clearAdminMediaAttachments();
        }
    }

    function clearAdminMediaAttachments() {
        document.getElementById('admin-img-input').value = '';
        document.getElementById('admin-file-input').value = '';
        document.getElementById('admin-media-preview-container').classList.add('hidden');
        document.getElementById('admin-image-thumb-box').classList.add('hidden');
        document.getElementById('admin-file-icon-box').classList.add('hidden');
        document.getElementById('admin-msg-input').setAttribute('required', 'required');
    }

    function buildAdminMsgHtml(msg) {
        const isMe = msg.user_id == adminUserId;
        const msgId = msg.id;
        const formattedTime = msg.created_at ? new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
        const safeMsgText = msg.message ? msg.message.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;") : '';
        
        // ឈ្មោះអតិថិជន និងអក្សរកាត់
        const displayName = isMe ? 'អ្នកគ្រប់គ្រង' : (msg.user_name || partnerNameDefault);
        const displayInitial = isMe ? 'A' : (displayName ? displayName.charAt(0).toUpperCase() : partnerInitialDefault);

        let mediaHtml = '';
        if (msg.images) {
            mediaHtml += `<div class="mt-2 rounded-xl overflow-hidden border border-white/20">
                <a href="${adminStorageUrl}/${msg.images}" class="spotlight block cursor-pointer" title="ចុចដើម្បីមើលរូបភាពធំ">
                    <img src="${adminStorageUrl}/${msg.images}" class="max-h-64 w-auto rounded-xl object-cover hover:scale-105 transition-transform duration-300" onload="scrollToAdminBottom()">
                </a>
            </div>`;
        }
        if (msg.file_path) {
            mediaHtml += `<div class="mt-2 pt-2 border-t ${isMe ? 'border-white/20' : 'border-gray-200 dark:border-gray-700'}">
                <a href="${adminStorageUrl}/${msg.file_path}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold underline hover:opacity-80">
                    <i class="fa-solid fa-file-arrow-down text-base"></i> ទាញយកឯកសារ
                </a>
            </div>`;
        }

        let editBtn = (msg.message && isMe) ? `
            <button type="button" onclick="editMessage(${msgId}, \`${safeMsgText}\`)" class="w-7 h-7 rounded-xl bg-white dark:bg-gray-800 text-gray-500 hover:text-blue-600 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center text-xs transition cursor-pointer" title="កែសម្រួល">
                <i class="fa-solid fa-pen"></i>
            </button>` : '';

        let statusCheck = isMe ? (msg.is_read ? '<span class="text-blue-500 text-[11px]" title="អតិថិជនបានអានរួច (Read)"><i class="fa-solid fa-check-double"></i></span>' : '<span class="text-gray-400 text-[11px]" title="បានផ្ញើ (Sent)"><i class="fa-solid fa-check"></i></span>') : '';

        return `
        <div class="w-full flex ${isMe ? 'justify-end' : 'justify-start'}">
            <div data-msg-id="${msgId}" class="group relative flex gap-3 items-end max-w-[85%] md:max-w-[75%] ${isMe ? 'flex-row-reverse' : 'flex-row'}">
                ${!isMe ? `<div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mb-1 shadow-sm" title="${displayName}">${displayInitial}</div>` : ''}
                <div class="space-y-1 min-w-0">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 px-1 ${isMe ? 'text-right' : 'text-left'}">
                        ${displayName}
                    </p>
                    <div class="p-3.5 rounded-2xl text-sm shadow-sm relative group/bubble transition-all ${isMe ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-br-xs' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-bl-xs border border-gray-100 dark:border-gray-700/70'}">
                        ${safeMsgText ? `<p class="leading-relaxed whitespace-pre-line break-words">${safeMsgText}</p>` : ''}
                        ${mediaHtml}
                    </div>
                    <div class="flex items-center gap-1.5 px-1 ${isMe ? 'justify-end' : 'justify-start'}">
                        <p class="text-[10px] text-gray-400 font-medium">${formattedTime}</p>
                        ${statusCheck}
                    </div>
                </div>
                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center gap-1 pb-4">
                    ${editBtn}
                    <button type="button" onclick="confirmAdminDeleteMessage(${msgId})" class="w-7 h-7 rounded-xl bg-white dark:bg-gray-800 text-gray-500 hover:text-red-600 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center text-xs transition cursor-pointer" title="លុបសារ">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        scrollToAdminBottom();

        async function syncAdminMessages() {
            @if($activeConversation)
            try {
                let res = await fetch(`/chat/fetch/{{ $activeConversation->id }}`, {
                    headers: { 'Accept': 'application/json' }
                });
                let messages = await res.json();
                const box = document.getElementById('admin-chat-box');
                if (Array.isArray(messages) && box) {
                    let hasNewCustomerMsg = false;
                    let hasAnyNewMsg = false;

                    messages.forEach(msg => {
                        let existing = box.querySelector(`[data-msg-id="${msg.id}"]`);
                        if (!existing) {
                            box.insertAdjacentHTML('beforeend', buildAdminMsgHtml(msg));
                            hasAnyNewMsg = true;
                            if (msg.user_id != adminUserId) {
                                hasNewCustomerMsg = true;
                            }
                        } else {
                            if (msg.user_id == adminUserId && msg.is_read) {
                                let checkEl = existing.querySelector('.fa-check');
                                if (checkEl) {
                                    checkEl.parentElement.outerHTML = '<span class="text-blue-500 text-[11px]" title="អតិថិជនបានអានរួច (Read)"><i class="fa-solid fa-check-double"></i></span>';
                                }
                            }
                        }
                    });

                    if (hasNewCustomerMsg) {
                        playAdminChime();
                    }

                    if (hasAnyNewMsg) {
                        scrollToAdminBottom();
                    }
                }
            } catch (e) {
                console.error(e);
            }
            @endif
        }

        @if($activeConversation)
        setInterval(syncAdminMessages, 2500);
        @endif

        const adminForm = document.getElementById('admin-chat-form');
        if (adminForm) {
            adminForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = document.getElementById('admin-send-btn');
                const msgInput = document.getElementById('admin-msg-input');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i>';
                }
                try {
                    let res = await fetch(adminForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(adminForm)
                    });
                    let data = await res.json();
                    if (data.success || data.id) {
                        adminForm.reset();
                        if (msgInput) msgInput.style.height = 'auto';
                        clearAdminMediaAttachments();
                        await syncAdminMessages();
                        scrollToAdminBottom();
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<span>ផ្ញើ</span> <i class="fa-solid fa-paper-plane text-xs"></i>';
                    }
                }
            });
        }
    });

    function editMessage(msgId, oldText) {
        document.getElementById('admin-modal-edit-msg-id').value = msgId;
        document.getElementById('admin-modal-edit-text').value = oldText;
        document.getElementById('admin-edit-modal').classList.remove('hidden');
    }

    function closeAdminEditModal() {
        document.getElementById('admin-edit-modal').classList.add('hidden');
    }

    async function submitAdminEditModal() {
        const msgId = document.getElementById('admin-modal-edit-msg-id').value;
        const newText = document.getElementById('admin-modal-edit-text').value;
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
                body: JSON.stringify({ message: newText })
            });
            let data = await res.json();
            if (data.success) {
                closeAdminEditModal();
                const box = document.getElementById('admin-chat-box');
                let el = box ? box.querySelector(`[data-msg-id="${msgId}"] p`) : null;
                if (el) el.innerText = newText;
                else location.reload();
            }
        } catch (e) {
            console.error(e);
        }
    }

    function confirmAdminDeleteMessage(msgId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'បញ្ជាក់ការលុបសារ',
                text: 'តើអ្នកប្រាកដជាចង់លុបសារនេះមែនទេ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash mr-1"></i> បាទ/ចាស, លុបសារ',
                cancelButtonText: 'បោះបង់',
                customClass: {
                    popup: 'rounded-3xl shadow-2xl dark:bg-gray-900 dark:text-white',
                    confirmButton: 'rounded-xl font-bold px-4 py-2.5 shadow-md',
                    cancelButton: 'rounded-xl font-bold px-4 py-2.5'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
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
                            const box = document.getElementById('admin-chat-box');
                            let el = box ? box.querySelector(`[data-msg-id="${msgId}"]`) : null;
                            if (el) el.remove();
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }
            });
        } else {
            document.getElementById('admin-delete-modal-title').innerText = 'បញ្ជាក់ការលុបសារ';
            document.getElementById('admin-delete-modal-desc').innerText = 'តើអ្នកប្រាកដជាចង់លុបសារនេះមែនទេ?';
            document.getElementById('admin-modal-delete-type').value = 'message';
            document.getElementById('admin-modal-delete-id').value = msgId;
            document.getElementById('admin-delete-modal').classList.remove('hidden');
        }
    }

    function confirmAdminDeleteConversation(convId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '⚠️ បញ្ជាក់ការលុបការជជែក',
                text: 'តើអ្នកប្រាកដជាចង់លុបប្រវត្តិការជជែកនេះ និងសារទាំងអស់ចោលមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash-can mr-1"></i> បាទ/ចាស, លុបការជជែក',
                cancelButtonText: 'បោះបង់',
                customClass: {
                    popup: 'rounded-3xl shadow-2xl dark:bg-gray-900 dark:text-white',
                    confirmButton: 'rounded-xl font-bold px-4 py-2.5 shadow-md',
                    cancelButton: 'rounded-xl font-bold px-4 py-2.5'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        let res = await fetch(`/admin/conversations/${convId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        let data = await res.json();
                        if (data.success) {
                            Swal.fire({
                                title: 'បានលុបរួចរាល់!',
                                text: 'ប្រវត្តិការជជែកត្រូវបានលុបដោយជោគជ័យ។',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('messages.index') }}";
                            });
                        } else {
                            location.reload();
                        }
                    } catch (e) {
                        console.error(e);
                        // Fallback form submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/conversations/${convId}`;
                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        form.appendChild(csrf);
                        form.appendChild(method);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            });
        } else {
            document.getElementById('admin-delete-modal-title').innerText = 'បញ្ជាក់ការលុបការជជែក';
            document.getElementById('admin-delete-modal-desc').innerText = 'តើអ្នកប្រាកដជាចង់លុបការជជែកនេះ និងសារទាំងអស់មែនទេ?';
            document.getElementById('admin-modal-delete-type').value = 'conversation';
            document.getElementById('admin-modal-delete-id').value = convId;
            document.getElementById('admin-delete-modal').classList.remove('hidden');
        }
    }

    function closeAdminDeleteModal() {
        document.getElementById('admin-delete-modal').classList.add('hidden');
    }

    async function submitAdminDeleteModal() {
        const type = document.getElementById('admin-modal-delete-type').value;
        const targetId = document.getElementById('admin-modal-delete-id').value;
        if (!targetId) return;

        if (type === 'message') {
            try {
                let res = await fetch(`/chat/messages/${targetId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                let data = await res.json();
                if (data.success) {
                    closeAdminDeleteModal();
                    const box = document.getElementById('admin-chat-box');
                    let el = box ? box.querySelector(`[data-msg-id="${targetId}"]`) : null;
                    if (el) el.remove();
                }
            } catch (e) {
                console.error(e);
            }
        } else if (type === 'conversation') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/conversations/${targetId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection