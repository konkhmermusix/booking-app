// ស្ដាប់ការបាញ់សារមកវិញ
Echo.private(`chat.${conversationId}`).listen("MessageSent", (e) => {
    console.log("សារថ្មី:", e.message);

    // ហៅ function ដើម្បីបង្ហាញសារលើ Screen
    appendMessageToUI(e.message);

    // Scroll ទៅក្រោមគេបង្អស់
    const chatWindow = document.getElementById("chat-messages");
    chatWindow.scrollTop = chatWindow.scrollHeight;
});

function appendMessageToUI(msg) {
    const chatContainer = document.getElementById("chat-messages");
    const isMe = msg.sender_id === currentUserId; // ឆែកមើលថាជារបស់យើង ឬគេ

    const html = `
        <div class="flex ${isMe ? "justify-end" : "justify-start"} mb-3">
            <div class="${
                isMe ? "bg-blue-600 text-white" : "bg-gray-200 text-black"
            } p-3 rounded-lg max-w-[80%]">
                ${msg.message ? `<p>${msg.message}</p>` : ""}
                ${
                    msg.file_path
                        ? `<img src="/storage/${msg.file_path}" class="rounded mt-2 w-full">`
                        : ""
                }
            </div>
        </div>
    `;
    chatContainer.insertAdjacentHTML("beforeend", html);
}
