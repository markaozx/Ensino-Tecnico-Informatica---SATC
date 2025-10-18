// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAuth } from "firebase/auth";
import { getFirestore } from "firebase/firestore";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyDbnCuEePNwLX4NxCchDN0ZqI0Q4KqMeKk",
  authDomain: "mobile3190marcus.firebaseapp.com",
  projectId: "mobile3190marcus",
  storageBucket: "mobile3190marcus.firebasestorage.app",
  messagingSenderId: "990584571941",
  appId: "1:990584571941:web:563b3d4736b35c002b57cc",
  measurementId: "G-N50CTTQPP2"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
export const auth = getAuth(app);
export const db = getFirestore(app);