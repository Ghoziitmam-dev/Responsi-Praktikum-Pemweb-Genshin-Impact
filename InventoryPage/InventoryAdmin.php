<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Inventory</title>
    <link rel="stylesheet" href="admin_inventory.css">
</head>
<body>

<div class="main-wrapper">
    <div class="admin-container">
        <div class="header-section">
            <h1>Manajemen Inventory</h1>
            <button class="btn-primary">+ Tambah Item</button>
        </div>

        <div class="table-scroll">
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Item</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $items = [
                        ["Agnidus Agate Chunk.png", "Agnidus Agate Chunk", "x1"],
                        ["Agnidus Agate Gemstone.png", "Agnidus Agate Gemstone", "x1"],
                        ["Brilliant Diamond Silver.png", "Brilliant Diamond Silver", "x3"],
                        ["Hoarfrost Core.png", "Hoarfrost Core", "x5"],
                        ["Illusory Leafcoil.png", "Illusory Leafcoil", "x12"],
                        ["Lion's Roar.png", "Lion's Roar", "x2"],
                        ["Nagadus Emerald Chunk.png", "Nagadus Emerald Chunk", "x8"],
                        ["Polar Star.png", "Polar Star", "x4"],
                        ["Prithiva Topaz Gemstone.png", "Prithiva Topaz Gemstone", "x15"],
                        ["Pseudo-Stamens.png", "Pseudo-Stamens", "x6"],
                        ["Quelled Creeper.png", "Quelled Creeper", "x4"],
                        ["Resalt Pillar.png", "Resalt Pillar", "x15"],
                        ["Rust.png", "Rust", "x6"],
                        ["Shivada Jade Chunk.png", "Shivada Jade Chunk", "x3"],
                        ["Skyward Atlas.png", "Skyward Atlas", "x5"],
                        ["Skyward Blade.png", "Skyward Blade", "x12"],
                        ["Spectral Nucleus.png", "Spectral Nucleus", "x2"],
                        ["Staff of Homa.png", "Staff of Homa", "x8"],
                        ["Stained Mask.png", "Stained Mask", "x4"],
                        ["The Bell.png", "The Bell", "x15"],
                        ["The Flute.png", "The Flute", "x6"],
                        ["Vajrada Amethyst Gemstone.png", "Vajrada Amethyst Gemstone", "x4"],
                        ["Varunada Lazurite Gemstone .png", "Varunada Lazurite Gemstone", "x15"],
                        ["Varunida Lazurite Silver.png", "Varunida Lazurite Silver", "x6"],
                        ["Verdict.png", "Verdict", "x6"]
                    ];

                    foreach ($items as $item) {
                        echo "<tr>";
                        echo "<td><img src='../image/" . $item[0] . "' alt='item'></td>";
                        echo "<td>" . $item[1] . "</td>";
                        echo "<td>" . $item[2] . "</td>";
                        echo "<td class='action-btns'>";
                        echo "<button class='btn-edit'>Edit</button>";
                        echo "<button class='btn-delete'>Hapus</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>