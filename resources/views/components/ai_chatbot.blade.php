<!-- Floating AI Assistant Chatbot Component (Pure Vanilla JS - Engkoy AI: Your Learning Assistant) -->
<div id="aiChatbotContainer" class="relative z-50 font-sans">
    
    <!-- Floating Trigger Button -->
    <div class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-50">
        <button id="aiChatbotTrigger" 
                type="button"
                class="group relative flex items-center gap-3 px-4 sm:px-5 py-2.5 rounded-full bg-zinc-950/90 border-2 border-blue-500/40 backdrop-blur-2xl text-white font-bold text-xs sm:text-sm shadow-[0_0_35px_-5px_rgba(59,130,246,0.4)] transition-all duration-300 hover:scale-105 hover:border-blue-400 hover:shadow-[0_0_50px_-5px_rgba(59,130,246,0.6)] cursor-pointer">
            
            <!-- Avatar Image with Pulsing Online Dot -->
            <div class="relative w-7 h-7 rounded-full overflow-hidden border border-blue-400/40 shrink-0">
                <img src="{{ asset('pictures/ai_avatar.png') }}" alt="Engkoy AI Avatar" class="w-full h-full object-cover">
                <span class="absolute bottom-0 right-0 w-2 h-2 bg-emerald-500 rounded-full border border-zinc-950"></span>
            </div>

            <!-- Icon & Label -->
            <div class="flex items-center gap-2">
                <span class="tracking-wide">Ask Engkoy AI</span>
                <i class="fa-solid fa-sparkles text-blue-400 text-xs group-hover:rotate-12 transition-transform"></i>
            </div>
        </button>
    </div>

    <!-- Chat Modal Window (HIDDEN BY DEFAULT) -->
    <div id="aiChatbotModal" 
         class="hidden fixed bottom-20 right-4 sm:right-6 z-50 w-[92vw] sm:w-[380px] md:w-[400px] h-[540px] max-h-[80vh] flex-col bg-[#0c0c14]/95 border border-white/15 backdrop-blur-3xl rounded-3xl shadow-[0_10px_60px_-15px_rgba(0,0,0,0.9)] overflow-hidden text-white transition-all duration-300">
        
        <!-- Header -->
        <div class="p-4 bg-gradient-to-r from-blue-900/40 via-zinc-950/80 to-indigo-900/40 border-b border-white/10 flex items-center justify-between shrink-0 relative">
            <div class="flex items-center gap-3">
                <div class="relative w-9 h-9 rounded-full bg-zinc-900 border border-blue-400/50 shadow-md overflow-hidden shrink-0">
                    <img src="{{ asset('pictures/ai_avatar.png') }}" alt="Engkoy AI" class="w-full h-full object-cover">
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full border border-zinc-950"></span>
                </div>
                <div>
                    <h3 class="font-bold text-xs sm:text-sm text-white leading-tight flex items-center gap-1.5">
                        <span>Engkoy AI</span>
                        <span class="px-1.5 py-0.2 rounded bg-blue-500/20 text-blue-400 font-mono text-[9px] font-extrabold uppercase">ASSISTANT</span>
                    </h3>
                    <p class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Online • Your Learning Assistant
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-1 text-gray-400">
                <button id="aiChatbotResetBtn" type="button" title="Reset Conversation" class="p-1.5 hover:text-white hover:bg-white/10 rounded-lg transition text-xs cursor-pointer">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
                <button id="aiChatbotCloseBtn" type="button" title="Close" class="p-1.5 hover:text-white hover:bg-white/10 rounded-lg transition text-xs cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm font-bold"></i>
                </button>
            </div>
        </div>

        <!-- Message List Container -->
        <div id="aiChatMessages" class="flex-1 p-4 overflow-y-auto space-y-3.5 text-xs scroll-smooth">
            
            <!-- Welcome Banner -->
            <div id="aiWelcomeBanner" class="p-3.5 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-gray-300 space-y-2">
                <p class="font-bold text-white text-xs flex items-center gap-1.5">
                    <span>👋 Hi! I am Engkoy AI, your learning assistant.</span>
                </p>
                <p class="text-[11px] text-gray-300 leading-relaxed">
                    I can help you <strong class="text-white font-semibold">check open coaching schedules</strong>, <strong class="text-white font-semibold">track your progress</strong>, <strong class="text-white font-semibold">compare course packages</strong>, and <strong class="text-white font-semibold">get daily practice routines</strong>!
                </p>


                <!-- Quick Suggestion Pills -->
                <div class="pt-2 flex flex-wrap gap-1.5">
                    <button type="button" onclick="aiSendQuickPrompt('How do I check open coaching schedules and book a session?')" class="px-2.5 py-1 rounded-full bg-blue-600/20 hover:bg-blue-600/40 border border-blue-500/30 text-[10px] text-blue-300 hover:text-white font-medium transition cursor-pointer text-left">
                        📅 Check Schedules
                    </button>
                    <button type="button" onclick="aiSendQuickPrompt('How do I track my learning progress and what should I practice next?')" class="px-2.5 py-1 rounded-full bg-indigo-600/20 hover:bg-indigo-600/40 border border-indigo-500/30 text-[10px] text-indigo-300 hover:text-white font-medium transition cursor-pointer text-left">
                        📊 Track Progress
                    </button>
                    <button type="button" onclick="aiSendQuickPrompt('What is the difference between Beginner and Intermediate packages?')" class="px-2.5 py-1 rounded-full bg-purple-600/20 hover:bg-purple-600/40 border border-purple-500/30 text-[10px] text-purple-300 hover:text-white font-medium transition cursor-pointer text-left">
                        🛍️ Compare Packages
                    </button>
                    <button type="button" onclick="aiSendQuickPrompt('Give me a 15-minute daily guitar practice routine for beginners!')" class="px-2.5 py-1 rounded-full bg-emerald-600/20 hover:bg-emerald-600/40 border border-emerald-500/30 text-[10px] text-emerald-300 hover:text-white font-medium transition cursor-pointer text-left">
                        🎸 15-Min Routine
                    </button>
                </div>
            </div>

            <!-- Typing Indicator (Hidden by default) -->
            <div id="aiTypingIndicator" class="hidden flex justify-start items-center gap-2 text-gray-400">
                <div class="w-6 h-6 rounded-full overflow-hidden border border-blue-500/40 flex items-center justify-center shrink-0">
                    <img src="{{ asset('pictures/ai_avatar.png') }}" alt="Engkoy AI" class="w-full h-full object-cover">
                </div>
                <div class="bg-zinc-900/90 border border-white/10 rounded-2xl rounded-tl-none px-4 py-2.5 flex items-center gap-1.5 shadow-md">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-bounce" style="animation-delay: 0ms;"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-bounce" style="animation-delay: 150ms;"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-bounce" style="animation-delay: 300ms;"></span>
                </div>
            </div>

        </div>

        <!-- Footer / Input Form -->
        <div class="p-3 bg-zinc-950/80 border-t border-white/10 shrink-0">
            <form id="aiChatbotForm" class="flex items-center gap-2">
                <input id="aiChatInput"
                       type="text" 
                       placeholder="Ask Engkoy AI about schedules, progress, or guitar practice..." 
                       class="flex-1 px-4 py-2.5 rounded-xl bg-zinc-900/80 border border-white/10 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition disabled:opacity-50">
                
                <button id="aiChatSubmitBtn"
                        type="submit" 
                        class="px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs transition shadow-md shadow-blue-600/30 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
(function() {
    let chatHistory = [];
    let isSending = false;
    const avatarUrl = "{{ asset('pictures/ai_avatar.png') }}";

    const triggerBtn = document.getElementById('aiChatbotTrigger');
    const closeBtn = document.getElementById('aiChatbotCloseBtn');
    const resetBtn = document.getElementById('aiChatbotResetBtn');
    const modal = document.getElementById('aiChatbotModal');
    const form = document.getElementById('aiChatbotForm');
    const input = document.getElementById('aiChatInput');
    const messagesContainer = document.getElementById('aiChatMessages');
    const typingIndicator = document.getElementById('aiTypingIndicator');

    if (!triggerBtn || !modal) return;

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        scrollToBottom();
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    triggerBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        if (modal.classList.contains('hidden')) {
            openModal();
        } else {
            closeModal();
        }
    });

    closeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        closeModal();
    });

    resetBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        chatHistory = [];
        const bubbles = messagesContainer.querySelectorAll('.ai-chat-bubble');
        bubbles.forEach(b => b.remove());
    });

    document.addEventListener('click', function(e) {
        if (!modal.classList.contains('hidden')) {
            if (!modal.contains(e.target) && !triggerBtn.contains(e.target)) {
                closeModal();
            }
        }
    });

    window.aiSendQuickPrompt = function(promptText) {
        if (input) {
            input.value = promptText;
            submitMessage();
        }
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitMessage();
    });

    async function submitMessage() {
        const text = input.value.trim();
        if (!text || isSending) return;

        isSending = true;
        input.value = '';
        input.disabled = true;

        appendUserMessage(text);
        chatHistory.push({ role: 'user', content: text });
        scrollToBottom();

        typingIndicator.classList.remove('hidden');
        messagesContainer.appendChild(typingIndicator);
        scrollToBottom();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const response = await fetch('/api/ai-chatbot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    message: text,
                    history: chatHistory.slice(-6)
                })
            });

            const data = await response.json();
            typingIndicator.classList.add('hidden');

            if (data.status === 'success' && data.reply) {
                appendAssistantMessage(data.reply);
                chatHistory.push({ role: 'assistant', content: data.reply });
            } else {
                appendAssistantMessage('Sorry, an error occurred while processing your request. Please try again.');
            }
        } catch (err) {
            console.error('Chatbot error:', err);
            typingIndicator.classList.add('hidden');
            appendAssistantMessage('Network issue. Please check your internet connection.');
        } finally {
            isSending = false;
            input.disabled = false;
            input.focus();
            scrollToBottom();
        }
    }

    function appendUserMessage(text) {
        const div = document.createElement('div');
        div.className = 'flex justify-end ai-chat-bubble';
        div.innerHTML = `
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl rounded-tr-none px-4 py-2.5 max-w-[85%] shadow-md">
                <div class="whitespace-pre-line text-[11px] leading-relaxed">${escapeHtml(text)}</div>
            </div>
        `;
        messagesContainer.insertBefore(div, typingIndicator);
    }

    function appendAssistantMessage(text) {
        const div = document.createElement('div');
        div.className = 'flex justify-start ai-chat-bubble';
        div.innerHTML = `
            <div class="w-6 h-6 rounded-full overflow-hidden border border-blue-500/40 flex items-center justify-center shrink-0 mr-2 mt-1">
                <img src="${avatarUrl}" alt="Engkoy AI" class="w-full h-full object-cover">
            </div>
            <div class="bg-zinc-900/90 border border-white/10 text-gray-200 rounded-2xl rounded-tl-none px-4 py-2.5 max-w-[85%] leading-relaxed shadow-md space-y-1.5">
                <div class="whitespace-pre-line text-[11px] leading-relaxed">${formatMarkdown(text)}</div>
            </div>
        `;
        messagesContainer.insertBefore(div, typingIndicator);
    }

    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 50);
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function formatMarkdown(text) {
        if (!text) return '';
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong class="text-white font-bold">$1</strong>')
            .replace(/\*(.*?)\*/g, '<em class="italic">$1</em>')
            .replace(/\n/g, '<br>');
    }
})();
</script>
