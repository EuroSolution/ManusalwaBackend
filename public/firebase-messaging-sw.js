importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyA81hB4BMSN8lyUDs-JM7XW1c9cPuuN0wc",
    projectId: "manusalwa-ed3ed",
    messagingSenderId: "573382697142",
    appId: "1:573382697142:web:151650f7945a43b93a0617"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
