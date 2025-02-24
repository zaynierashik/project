// For both full and bubble chatbot versions
document.addEventListener("DOMContentLoaded", function () {
    const chatbotToggle = document.getElementById("chatbot-toggle");
    const chatbotContainer = document.getElementById("chatbot-container");
    const chatbotClose = document.getElementById("chatbot-close");
    const chatbotMessages = document.getElementById("chatbot-messages");
    const chatbotPlaceholder = document.getElementById("chatbot-placeholder");
    const chatbotInput = document.getElementById("chatbot-input");
    const chatbotSend = document.getElementById("chatbot-send");

    // Check if toggle and close buttons exist before adding event listeners
    if (chatbotToggle) {
        chatbotToggle.addEventListener("click", () => chatbotContainer.classList.toggle("hidden"));
    }
    if (chatbotClose) {
        chatbotClose.addEventListener("click", () => chatbotContainer.classList.add("hidden"));
    }

    chatbotSend.addEventListener("click", sendMessage);
    chatbotInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") sendMessage();
    });

    // Regex patterns
    const institutionCountRegex = /how many institution(s)?|count institution(s)?|institution count(s)?/i;
    const courseCountRegex = /how many course(s)?|count course(s)?|course(s)? count/i;
    const institutionListRegex = /institution(s)? list|list institution(s)?|show me institution(s)?/i;
    const courseListRegex = /course(s)? list|list course(s)?|show me course(s)?/i;
    const institutionQueryRegex = /tell me about (.+)/i;
    const courseQueryRegex = /tell me about (.+)/i;
    const helpQueryRegex = /help|what can you do|what can I ask/i;
    const aboutQueryRegex = /about you|who are you|what is this/i;
    const greetingQueryRegex = /hello|hi|hey|howdy/i;
    const institutionTopicRegex = /institution(s)?|school(s)?|college(s)?/i;
    const courseTopicRegex = /course(s)?|program(s)?|degree(s)?/i;
    const unclearQueryRegex = /[^\w\s]+/i;
    const usageHelpRegex = /how to use|how do I use|usage/i;
    const faqRegex = /frequently asked questions|faq/i;

    function sendMessage() {
        const userMessage = chatbotInput.value.trim();
        if (!userMessage) return;

        if (chatbotPlaceholder) {
            chatbotPlaceholder.remove();
        }

        addMessage("Me", userMessage, "text-right text-transparent bg-clip-text bg-gradient-to-tr from-indigo-600 to-violet-600");
        chatbotInput.value = "";

        if (institutionCountRegex.test(userMessage)) {
            fetchCount("/api/chatbotinstitutions/", "institutions");
        } else if (courseCountRegex.test(userMessage)) {
            fetchCount("/api/chatbotcourses/", "courses");
        } else if (institutionListRegex.test(userMessage)) {
            fetchData("/api/chatbotinstitutions/", "Here are some institutions:");
        } else if (courseListRegex.test(userMessage)) {
            fetchData("/api/chatbotcourses/", "Here are some courses:");
        } else if (institutionQueryRegex.test(userMessage)) {
            const institutionName = userMessage.match(institutionQueryRegex)[1];
            fetchInstitutionDetails(institutionName);
        } else if (courseQueryRegex.test(userMessage)) {
            const courseName = userMessage.match(courseQueryRegex)[1].trim();
            fetchCourseDetails(courseName);
        } else if (helpQueryRegex.test(userMessage)) {
            addMessage("Bot", "I can help with institutions, courses, and more. Just ask!", "text-gray-600");
        } else if (aboutQueryRegex.test(userMessage)) {
            addMessage("Bot", "I am a chatbot here to assist you with institution and course information.", "text-gray-600");
        } else if (greetingQueryRegex.test(userMessage)) {
            addMessage("Bot", "Hello! How can I assist you today?", "text-gray-600");
        } else if (institutionTopicRegex.test(userMessage)) {
            addMessage("Bot", "You can ask about institutions or schools. Try 'institution count' or 'list institutions'.", "text-gray-600");
        } else if (courseTopicRegex.test(userMessage)) {
            addMessage("Bot", "You can ask about courses or programs. Try 'course count' or 'list courses'.", "text-gray-600");
        } else if (usageHelpRegex.test(userMessage)) {
            addMessage("Bot", "Just ask about institutions, courses, or other details like count, list, or specific information.", "text-gray-600");
        } else if (faqRegex.test(userMessage)) {
            addMessage("Bot", "You can ask me about institutions, courses, and their details. I will try my best to assist you!", "text-gray-600");
        } else if (unclearQueryRegex.test(userMessage)) {
            addMessage("Bot", "I'm not sure I understand that. Please try asking about institutions or courses.", "text-gray-600");
        } else {
            addMessage("Bot", "I can help with institutions and courses. Try asking about them!", "text-gray-600");
        }
    }

    function fetchCount(url, type) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const count = data.length;
                addMessage("Bot", `There are currently <strong>${count}</strong> ${type}.`, "text-gray-600");
            })
            .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
    }

    function fetchInstitutionDetails(name) {
        const encodedName = encodeURIComponent(name);
        fetch(`/api/chatbotinstitutions/${encodedName}/`)
            .then(response => response.json())
            .then(data => {
                const details = data.overview ? data.overview : "Institution not found!";
                addMessage("Bot", details, "text-gray-600");
            })
            .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
    }

    function fetchCourseDetails(name) {
        fetch(`/api/chatbotcourses/${encodeURIComponent(name)}`)
            .then(response => response.json())
            .then(data => {
                const details = data && data.about ? data.about : "Course not found!";
                addMessage("Bot", details, "text-gray-600");
            })
            .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
    }

    function fetchData(url, responseText) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const items = data.map(item => `- ${item.name}`).join("<br>");
                addMessage("Bot", `${responseText} <br>${items}`, "text-gray-600");
            })
            .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
    }

    function addMessage(sender, text, textStyle) {
        const messageWrapper = document.createElement("div");
        messageWrapper.classList.add("flex", "w-full");

        const messageElement = document.createElement("div");
        messageElement.classList.add(
            "p-2", "rounded-lg", "max-w-2xl", "break-words", "shadow-sm", "text-[13px]"
        );

        if (sender === "Me") {
            messageWrapper.classList.add("justify-end");
            messageElement.classList.add("bg-gray-500", "text-white", "rounded-br-none");
        } else {
            messageWrapper.classList.add("justify-start");
            messageElement.classList.add("bg-gray-200", "text-gray-800", "rounded-bl-none");
        }

        messageElement.innerHTML = `<strong>${sender}:</strong> ${text}`;
        messageWrapper.appendChild(messageElement);
        chatbotMessages.appendChild(messageWrapper);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
});


// For bubble chatbot version
// document.addEventListener("DOMContentLoaded", function () {
//     const chatbotToggle = document.getElementById("chatbot-toggle");
//     const chatbotContainer = document.getElementById("chatbot-container");
//     const chatbotClose = document.getElementById("chatbot-close");
//     const chatbotMessages = document.getElementById("chatbot-messages");
//     const chatbotInput = document.getElementById("chatbot-input");
//     const chatbotSend = document.getElementById("chatbot-send");

//     // Toggle chatbot visibility
//     chatbotToggle.addEventListener("click", () => chatbotContainer.classList.toggle("hidden"));
//     chatbotClose.addEventListener("click", () => chatbotContainer.classList.add("hidden"));

//     chatbotSend.addEventListener("click", sendMessage);
//     chatbotInput.addEventListener("keypress", function (event) {
//         if (event.key === "Enter") sendMessage();
//     });

//     // Regex patterns
//     const institutionCountRegex = /how many institution(s)?|count institution(s)?|institution count(s)?/i;
//     const courseCountRegex = /how many course(s)?|count course(s)?|course(s)? count/i;
//     const institutionListRegex = /institution(s)? list|list institution(s)?|show me institution(s)?/i;
//     const courseListRegex = /course(s)? list|list course(s)?|show me course(s)?/i;
//     const institutionQueryRegex = /tell me about (.+)/i;
//     const courseQueryRegex = /tell me about (.+)/i;
//     const helpQueryRegex = /help|what can you do|what can I ask/i;
//     const aboutQueryRegex = /about you|who are you|what is this/i;
//     const greetingQueryRegex = /hello|hi|hey|howdy/i;
//     const institutionTopicRegex = /institution(s)?|school(s)?|college(s)?/i;
//     const courseTopicRegex = /course(s)?|program(s)?|degree(s)?/i;
//     const unclearQueryRegex = /[^\w\s]+/i;
//     const usageHelpRegex = /how to use|how do I use|usage/i;
//     const faqRegex = /frequently asked questions|faq/i;

//     // Handle sending a message
//     function sendMessage() {
//         const userMessage = chatbotInput.value.trim();
//         if (!userMessage) return;

//         addMessage("Me", userMessage, "text-right text-transparent bg-clip-text bg-gradient-to-tr from-indigo-600 to-violet-600");
//         chatbotInput.value = "";

//         // Handle different query types
//         if (institutionCountRegex.test(userMessage)) {
//             fetchCount("/api/chatbotinstitutions/", "institutions");
//         } else if (courseCountRegex.test(userMessage)) {
//             fetchCount("/api/chatbotcourses/", "courses");
//         } else if (institutionListRegex.test(userMessage)) {
//             fetchData("/api/chatbotinstitutions/", "Here are some institutions:");
//         } else if (courseListRegex.test(userMessage)) {
//             fetchData("/api/chatbotcourses/", "Here are some courses:");
//         } else if (institutionQueryRegex.test(userMessage)) {
//             const institutionName = userMessage.match(institutionQueryRegex)[1];
//             fetchInstitutionDetails(institutionName);
//         } else if (courseQueryRegex.test(userMessage)) {
//             const courseName = userMessage.match(courseQueryRegex)[1].trim();
//         fetchCourseDetails(courseName);
//         } else if (helpQueryRegex.test(userMessage)) {
//             addMessage("Bot", "I can help with institutions, courses, and more. Just ask!", "text-gray-600");
//         } else if (aboutQueryRegex.test(userMessage)) {
//             addMessage("Bot", "I am a chatbot here to assist you with institution and course information.", "text-gray-600");
//         } else if (greetingQueryRegex.test(userMessage)) {
//             addMessage("Bot", "Hello! How can I assist you today?", "text-gray-600");
//         } else if (institutionTopicRegex.test(userMessage)) {
//             addMessage("Bot", "You can ask about institutions or schools. Try 'institution count' or 'list institutions'.", "text-gray-600");
//         } else if (courseTopicRegex.test(userMessage)) {
//             addMessage("Bot", "You can ask about courses or programs. Try 'course count' or 'list courses'.", "text-gray-600");
//         } else if (usageHelpRegex.test(userMessage)) {
//             addMessage("Bot", "Just ask about institutions, courses, or other details like count, list, or specific information.", "text-gray-600");
//         } else if (faqRegex.test(userMessage)) {
//             addMessage("Bot", "You can ask me about institutions, courses, and their details. I will try my best to assist you!", "text-gray-600");
//         } else if (unclearQueryRegex.test(userMessage)) {
//             addMessage("Bot", "I'm not sure I understand that. Please try asking about institutions or courses.", "text-gray-600");
//         } else {
//             addMessage("Bot", "I can help with institutions and courses. Try asking about them!", "text-gray-600");
//         }
//     }

//     // Fetch institution/course count
//     function fetchCount(url, type) {
//         fetch(url)
//             .then(response => response.json())
//             .then(data => {
//                 const count = data.length;
//                 addMessage("Bot", `There are currently <strong>${count}</strong> ${type}.`, "text-gray-600");
//             })
//             .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
//     }

//     // Fetch institution/course details
//     function fetchInstitutionDetails(name) {
//         const encodedName = encodeURIComponent(name);
//         fetch(`/api/chatbotinstitutions/${encodedName}/`)
//             .then(response => response.json())
//             .then(data => {
//                 const details = data.overview ? data.overview : "Institution not found!";
//                 addMessage("Bot", details, "text-gray-600");
//             })
//             .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
//     }
    
//     function fetchCourseDetails(name) {
//         fetch(`/api/chatbotcourses/${encodeURIComponent(name)}`)
//             .then(response => response.json())
//             .then(data => {
//                 const details = data && data.about ? data.about : "Course not found!";
//                 addMessage("Bot", details, "text-gray-600");
//             })
//             .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
//     }

//     // Fetch list of institutions/courses
//     function fetchData(url, responseText) {
//         fetch(url)
//             .then(response => response.json())
//             .then(data => {
//                 const items = data.map(item => `- ${item.name}`).join("<br>");
//                 addMessage("Bot", `${responseText} <br>${items}`, "text-gray-600");
//             })
//             .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
//     }

//     // Add message to the chat
//     function addMessage(sender, text, textStyle) {
//         const messageWrapper = document.createElement("div");
//         messageWrapper.classList.add("flex", "w-full");

//         const messageElement = document.createElement("div");
//         messageElement.classList.add(
//             "p-2", "rounded-lg", "max-w-xs", "break-words", "shadow-sm", "text-[13px]"
//         );

//         // Apply different styles for user and bot messages
//         if (sender === "Me") {
//             messageWrapper.classList.add("justify-end");
//             messageElement.classList.add("bg-gray-500", "text-white", "rounded-br-none");
//         } else {
//             messageWrapper.classList.add("justify-start");
//             messageElement.classList.add("bg-gray-200", "text-gray-800", "rounded-bl-none");
//         }

//         messageElement.innerHTML = `<strong>${sender}:</strong> ${text}`;
//         messageWrapper.appendChild(messageElement);
//         chatbotMessages.appendChild(messageWrapper);
//         chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
//     }
// });


// For full chatbot version
// document.addEventListener("DOMContentLoaded", function () {
//     const chatbotContainer = document.getElementById("chatbot-container");
//     const chatbotMessages = document.getElementById("chatbot-messages");
//     const chatbotInput = document.getElementById("chatbot-input");
//     const chatbotSend = document.getElementById("chatbot-send");

//     chatbotSend.addEventListener("click", sendMessage);
//     chatbotInput.addEventListener("keypress", function (event) {
//         if (event.key === "Enter") sendMessage();
//     });

//     // Regex patterns
//     const institutionCountRegex = /how many institution(s)?|count institution(s)?|institution count(s)?/i;
//     const courseCountRegex = /how many course(s)?|count course(s)?|course(s)? count/i;
//     const institutionListRegex = /institution(s)? list|list institution(s)?|show me institution(s)?/i;
//     const courseListRegex = /course(s)? list|list course(s)?|show me course(s)?/i;
//     const institutionQueryRegex = /tell me about (.+)/i;
//     const courseQueryRegex = /tell me about (.+)/i;
//     const helpQueryRegex = /help|what can you do|what can I ask/i;
//     const aboutQueryRegex = /about you|who are you|what is this/i;
//     const greetingQueryRegex = /hello|hi|hey|howdy/i;
//     const institutionTopicRegex = /institution(s)?|school(s)?|college(s)?/i;
//     const courseTopicRegex = /course(s)?|program(s)?|degree(s)?/i;
//     const unclearQueryRegex = /[^\w\s]+/i;
//     const usageHelpRegex = /how to use|how do I use|usage/i;
//     const faqRegex = /frequently asked questions|faq/i;

//     function sendMessage() {
//         const userMessage = chatbotInput.value.trim();
//         if (!userMessage) return;

//         addMessage("Me", userMessage, "text-right text-transparent bg-clip-text bg-gradient-to-tr from-indigo-600 to-violet-600");
//         chatbotInput.value = "";

//         // Handle different query types
//         if (institutionCountRegex.test(userMessage)) {
//             fetchCount("/api/chatbotinstitutions/", "institutions");
//         } else if (courseCountRegex.test(userMessage)) {
//             fetchCount("/api/chatbotcourses/", "courses");
//         } else if (institutionListRegex.test(userMessage)) {
//             fetchData("/api/chatbotinstitutions/", "Here are some institutions:");
//         } else if (courseListRegex.test(userMessage)) {
//             fetchData("/api/chatbotcourses/", "Here are some courses:");
//         } else if (institutionQueryRegex.test(userMessage)) {
//             const institutionName = userMessage.match(institutionQueryRegex)[1];
//             fetchInstitutionDetails(institutionName);
//         } else if (courseQueryRegex.test(userMessage)) {
//             const courseName = userMessage.match(courseQueryRegex)[1].trim();
//             fetchCourseDetails(courseName);
//         } else if (helpQueryRegex.test(userMessage)) {
//             addMessage("Bot", "I can help with institutions, courses, and more. Just ask!", "text-gray-600");
//         } else if (aboutQueryRegex.test(userMessage)) {
//             addMessage("Bot", "I am a chatbot here to assist you with institution and course information.", "text-gray-600");
//         } else if (greetingQueryRegex.test(userMessage)) {
//             addMessage("Bot", "Hello! How can I assist you today?", "text-gray-600");
//         } else if (institutionTopicRegex.test(userMessage)) {
//             addMessage("Bot", "You can ask about institutions or schools. Try 'institution count' or 'list institutions'.", "text-gray-600");
//         } else if (courseTopicRegex.test(userMessage)) {
//             addMessage("Bot", "You can ask about courses or programs. Try 'course count' or 'list courses'.", "text-gray-600");
//         } else if (usageHelpRegex.test(userMessage)) {
//             addMessage("Bot", "Just ask about institutions, courses, or other details like count, list, or specific information.", "text-gray-600");
//         } else if (faqRegex.test(userMessage)) {
//             addMessage("Bot", "You can ask me about institutions, courses, and their details. I will try my best to assist you!", "text-gray-600");
//         } else if (unclearQueryRegex.test(userMessage)) {
//             addMessage("Bot", "I'm not sure I understand that. Please try asking about institutions or courses.", "text-gray-600");
//         } else {
//             addMessage("Bot", "I can help with institutions and courses. Try asking about them!", "text-gray-600");
//         }
//     }

//     function fetchCount(url, type) {
//         fetch(url)
//             .then(response => response.json())
//             .then(data => {
//                 const count = data.length;
//                 addMessage("Bot", `There are currently <strong>${count}</strong> ${type}.`, "text-gray-600");
//             })
//             .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
//     }

//     function fetchInstitutionDetails(name) {
//         const encodedName = encodeURIComponent(name);
//         fetch(`/api/chatbotinstitutions/${encodedName}/`)
//             .then(response => response.json())
//             .then(data => {
//                 const details = data.overview ? data.overview : "Institution not found!";
//                 addMessage("Bot", details, "text-gray-600");
//             })
//             .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
//     }

//     function fetchCourseDetails(name) {
//         fetch(`/api/chatbotcourses/${encodeURIComponent(name)}`)
//             .then(response => response.json())
//             .then(data => {
//                 const details = data && data.about ? data.about : "Course not found!";
//                 addMessage("Bot", details, "text-gray-600");
//             })
//             .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
//     }

//     function fetchData(url, responseText) {
//         fetch(url)
//             .then(response => response.json())
//             .then(data => {
//                 const items = data.map(item => `- ${item.name}`).join("<br>");
//                 addMessage("Bot", `${responseText} <br>${items}`, "text-gray-600");
//             })
//             .catch(error => addMessage("Bot", "Sorry, something went wrong.", "text-red-500"));
//     }

//     function addMessage(sender, text, textStyle) {
//         const messageWrapper = document.createElement("div");
//         messageWrapper.classList.add("flex", "w-full");

//         const messageElement = document.createElement("div");
//         messageElement.classList.add(
//             "p-2", "rounded-lg", "max-w-xs", "break-words", "shadow-sm", "text-[13px]"
//         );

//         if (sender === "Me") {
//             messageWrapper.classList.add("justify-end");
//             messageElement.classList.add("bg-gray-500", "text-white", "rounded-br-none");
//         } else {
//             messageWrapper.classList.add("justify-start");
//             messageElement.classList.add("bg-gray-200", "text-gray-800", "rounded-bl-none");
//         }

//         messageElement.innerHTML = `<strong>${sender}:</strong> ${text}`;
//         messageWrapper.appendChild(messageElement);
//         chatbotMessages.appendChild(messageWrapper);
//         chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
//     }
// });