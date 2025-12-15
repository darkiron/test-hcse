// app.js — bootstrap: monte un layout unique et charge les routes (pages)
import { MainLayout } from './layout/MainLayout.js';

const root = document.getElementById('app');
root.appendChild(MainLayout());

// Charger routes/comportements après montage
import('./main.js');
