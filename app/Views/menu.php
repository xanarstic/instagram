<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    body {
        display: flex;
        margin: 0;
        font-family: Arial, sans-serif;
        background: #fafafa;
    }

    /* Sidebar */
    #sidebar {
        width: 250px;
        background-color: #fff;
        height: 100vh;
        padding-top: 20px;
        position: fixed;
        border-right: 1px solid #dbdbdb;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #sidebar nav ul {
        list-style-type: none;
        padding: 0;
        width: 100%;
    }

    #sidebar nav ul li {
        padding: 10px;
        text-align: center;
    }

    #sidebar nav ul li a {
        text-decoration: none;
        color: black;
        display: block;
        padding: 10px;
        font-weight: bold;
        transition: background 0.3s ease, color 0.3s ease;
    }

    #sidebar nav ul li a:hover {
        background-color: #efefef;
        border-radius: 10px;
    }

    /* Konten */
    #content {
        margin-left: 270px;
        padding: 20px;
        width: calc(100% - 270px);
    }

    #posts {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .post-container {
        background: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: left;
    }

    .post-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
    }

    .profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ccc;
    }

    .post-container img,
    .post-container video {
        max-width: 100%;
        border-radius: 10px;
    }

    .post-footer {
        padding: 10px;
    }
</style>
<div id="sidebar">
    <nav>
        <ul>
            <li><a href="<?= base_url('home/dashboard') ?>">🏠 Dashboard</a></li>
            <li><a href="<?= base_url('home/historyUser') ?>">📜 Laporan User</a></li>
            <li><a href="<?= base_url('home/logout') ?>">🚪 Logout</a></li>
        </ul>
    </nav>
</div>