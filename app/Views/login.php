<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }
    </style>
</head>

<body>

    <h2>Login</h2>
    <?php if (session()->getFlashdata('success')) : ?>
        <p class="success-message"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <p class="error-message"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('/home/dologin') ?>" method="post">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="#" id="openRegister">Daftar</a></p>

    <!-- Overlay dan Popup -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="registerPopup">
        <h3>Register</h3>
        <p id="registerMessage"></p>
        <form id="registerForm">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Daftar</button>
            <button type="button" id="closeRegister">Batal</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $("#openRegister").click(function() {
                $("#overlay, #registerPopup").fadeIn();
            });

            $("#closeRegister").click(function() {
                $("#overlay, #registerPopup").fadeOut();
            });

            $("#registerForm").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "<?= base_url('home/register') ?>",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            $("#registerMessage").html('<p class="success-message">' + response.message + '</p>');
                            setTimeout(function() {
                                location.reload(); // Refresh halaman setelah register
                            }, 2000);
                        } else {
                            $("#registerMessage").html('<p class="error-message">' + response.message + '</p>');
                        }
                    },
                    error: function() {
                        $("#registerMessage").html('<p class="error-message">Terjadi kesalahan, coba lagi!</p>');
                    }
                });
            });
        });
    </script>

</body>

</html>