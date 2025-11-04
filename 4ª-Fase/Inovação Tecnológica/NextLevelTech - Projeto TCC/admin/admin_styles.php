<?php
/**
 * Estilos padronizados para todas as páginas administrativas
 * Baseado no design do painel financeiro
 */
?>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
        --primary-color: #000;
        --accent-color: #FF6B00;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --text-primary: #000;
        --text-secondary: #666;
        --border-color: #e5e5e5;
        --bg-gray: #f5f5f5;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .topbar {
        background: #28243D;
        color: white;
        padding: 15px 25px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        font-weight: 500;
    }

    .topbar > div:first-child {
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .topbar > div:last-child {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .topbar a {
        color: white;
        text-decoration: none;
        padding: 10px 18px;
        background: #3A3550;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
        border: none;
    }

    .topbar a:hover {
        background: #4A4558;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    h1 {
        font-size: 32px;
        color: var(--text-primary);
        margin-bottom: 10px;
    }

    h2 {
        font-size: 24px;
        color: var(--text-primary);
        margin-bottom: 20px;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table thead {
        background: var(--bg-gray);
    }

    table th, table td {
        padding: 15px 12px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    table th {
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    table tr:hover {
        background: var(--bg-gray);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn {
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        display: inline-block;
        border: none;
        cursor: pointer;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: white;
        color: var(--text-primary);
        border: 2px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: var(--bg-gray);
        border-color: var(--text-primary);
        box-shadow: none;
    }

    .btn-danger {
        background: var(--danger-color);
    }

    .btn-success {
        background: var(--success-color);
    }

    .search-bar {
        margin-bottom: 20px;
    }

    .search-bar input {
        padding: 12px 16px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        width: 300px;
        max-width: 100%;
        background: white;
        color: var(--text-primary);
    }

    .search-bar button {
        padding: 12px 20px;
        margin-left: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        body {
            padding: 10px;
        }

        .topbar {
            flex-direction: column;
            text-align: center;
        }

        .card {
            padding: 20px;
        }

        table {
            font-size: 12px;
        }

        table th, table td {
            padding: 8px 6px;
        }
    }
</style>

