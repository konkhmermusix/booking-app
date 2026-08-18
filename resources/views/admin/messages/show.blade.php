@extends('layouts.admin')
@section('title', 'សារ')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-4 h-[calc(100vh-120px)] bg-white dark:bg-gray-900 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-800 text-xs"
    x-data="chatApp({
        conversationId: {{ $activeConversation->id ?? 'null' }},
        currentUserId: {{ auth()->id() }},
        initialMessages: @json($chatMessages ?? [])
    })">

    <!-- LEFT SIDEBAR -->
    <div class="xl:col-span-1 bg-white dark:bg-gray-900 border-r dark:border-gray-800 flex flex-col h-full overflow-y-auto p-2">

        @foreach($conversations as $conv)
        @php $partner = $conv->sender; @endphp

        <a href="{{ route('messages.show', $conv->id) }}"
            class="p-3 block rounded-xl mb-2 bg-gray-50 dark:bg-gray-800">

            <div class="font-bold text-gray-800 dark:text-white">
                {{ $partner->name }}
            </div>

            <div class="text-[10px] text-gray-400">
                Chat ID: {{ $conv->id }}
            </div>

        </a>
        @endforeach

    </div>

    <!-- CHAT BOX -->
    @if($activeConversation)

    <div class="xl:col-span-3 flex flex-col h-full">

        <!-- MESSAGES -->
        <div id="chat-messages-container"
            class="flex-1 overflow-y-auto p-4 space-y-3">

            <template x-for="msg in messages" :key="msg.id">

                <div>

                    <!-- ADMIN MESSAGE -->
                    <template x-if="msg.user_id === currentUserId">
                        <div class="text-right">
                            <div class="inline-block bg-lime-200 p-2 rounded-lg">
                                <p x-text="msg.message"></p>

                                <template x-if="msg.images">
                                    <img :src="'/storage/' + msg.images" class="w-40 mt-2">
                                </template>

                                <template x-if="msg.file_path">
                                    <a :href="'/storage/' + msg.file_path" target="_blank">File</a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- USER MESSAGE -->
                    <template x-if="msg.user_id !== currentUserId">
                        <div class="text-left">
                            <div class="inline-block bg-white p-2 rounded-lg border">
                                <p x-text="msg.message"></p>

                                <template x-if="msg.images">
                                    <img :src="'/storage/' + msg.images" class="w-40 mt-2">
                                </template>

                                <template x-if="msg.file_path">
                                    <a :href="'/storage/' + msg.file_path" target="_blank">Download</a>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>

            </template>

        </div>

        <!-- INPUT -->
        <div class="p-3 border-t">

            <form @submit.prevent="sendMessage()" class="flex gap-2">

                <input type="text"
                    x-model="newMessageText"
                    placeholder="វាយសារ..."
                    class="flex-1 border p-2 rounded">

                <input type="file" @change="handleFileChange($event,'image')">
                <input type="file" @change="handleFileChange($event,'file')">

                <button class="bg-green-500 text-white px-4 rounded">
                    Send
                </button>

            </form>

        </div>

    </div>

    @else
    <div class="xl:col-span-3 flex items-center justify-center">
        <p>សូមជ្រើស conversation</p>
    </div>
    @endif

</div>

@endsection

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatApp', (config) => ({
            conversationId: config.conversationId,
            currentUserId: config.currentUserId,
            messages: config.initialMessages,
            newMessageText: '',
            selectedFile: null,
            fileType: '',

            handleFileChange(e, type) {
                this.selectedFile = e.target.files[0];
                this.fileType = type;
            },

            sendMessage() {
                let formData = new FormData();
                formData.append('message', this.newMessageText);

                if (this.selectedFile) {
                    if (this.fileType === 'image') {
                        formData.append('images', this.selectedFile);
                    } else {
                        formData.append('file', this.selectedFile);
                    }
                }

                this.newMessageText = '';
                this.selectedFile = null;

                axios.post(`/admin/messages/${this.conversationId}`, formData)
                    .then(res => {
                        this.messages.push(res.data);
                        this.scrollToBottom();
                    });
            },

            scrollToBottom() {
                setTimeout(() => {
                    let el = document.getElementById('chat-messages-container');
                    el.scrollTop = el.scrollHeight;
                }, 100);
            }
        }));
    });
</script>