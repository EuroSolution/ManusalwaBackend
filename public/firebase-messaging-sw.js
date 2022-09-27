importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyAGX31HWcnrxSIycYLwwWpbhyFQTD9SrXc",
    projectId: "pizzeriaroma-ed2dd",
    messagingSenderId: "945625842302",
    appId: "1:945625842302:web:2fbb4b5d6d535e517d8e2f"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
