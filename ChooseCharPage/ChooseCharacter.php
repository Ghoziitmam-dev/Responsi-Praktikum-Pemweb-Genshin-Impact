<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genshin Impact - Choose Your Character</title>
    <link rel="stylesheet" href="ChooseCharacter.css">
</head>
<body>

    <div class="background-game"></div>

    <div class="main-container">
        
        <div class="header-pilih">
            <img src="../image/ChooseYourCharacter.png" alt="Pick Your Character" class="img-judul">
        </div>

        <form action="" method="POST" class="form-karakter">
            <div class="wrapper-karakter">
                
                <label class="kartu-item">
                    <input type="radio" name="pilih_char" value="Ororon" required>
                    <div class="konten-kartu">
                        <img src="../image/ororon.png" alt="Ororon">
                    </div>
                </label>

                <label class="kartu-item">
                    <input type="radio" name="pilih_char" value="Wriothesley">
                    <div class="konten-kartu">
                        <img src="../image/wriothesley.png" alt="Wriothesley">
                    </div>
                </label>

                <label class="kartu-item">
                    <input type="radio" name="pilih_char" value="Raiden">
                    <div class="konten-kartu">
                        <img src="../image/raiden.png" alt="Raiden">
                    </div>
                </label>

                <label class="kartu-item">
                    <input type="radio" name="pilih_char" value="Neuvillette">
                    <div class="konten-kartu">
                        <img src="../image/Neuvillete.png" alt="Neuvillette">
                    </div>
                </label>

                <label class="kartu-item">
                    <input type="radio" name="pilih_char" value="Kinich">
                    <div class="konten-kartu">
                        <img src="../image/kinich.png" alt="Kinich">
                    </div>
                </label>

            </div>

            <div class="footer-tombol">
                <button type="submit" class="btn-konfirmasi">Konfirmasi</button>
            </div>
        </form>

    </div>

</body>
</html>