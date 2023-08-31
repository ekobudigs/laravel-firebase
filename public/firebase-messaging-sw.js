importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
            apiKey: "AIzaSyAAHjc4M7TDVD2yvIom3c0pGKDr1MZKznI",
            authDomain: "notif-869c4.firebaseapp.com",
            projectId: "notif-869c4",
            storageBucket: "notif-869c4.appspot.com",
            messagingSenderId: "207422477758",
            appId: "1:207422477758:web:f21bc669fcc920839dd5ef"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});