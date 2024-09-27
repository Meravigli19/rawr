<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "vockey", "messi");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menangani form tambah/edit siswa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $kelas = $_POST["kelas"];
    $foto = "";

    if (isset($_FILES["foto"])) {
        $foto = "uploads/" . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);
    }

    if (isset($_POST["id"])) {
        // Update data
        $id = $_POST["id"];
        $query = "UPDATE siswa SET nama='$nama', kelas='$kelas', foto='$foto' WHERE id=$id";
    } else {
        // Insert data baru
        $query = "INSERT INTO siswa (nama, foto, kelas) VALUES ('$nama', '$foto', '$kelas')";
    }
    $conn->query($query);
    header("Location: index.php");
}

// Menangani penghapusan data
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM siswa WHERE id=$id");
    header("Location: index.php");
}

// Ambil semua data siswa
$result = $conn->query("SELECT * FROM siswa");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Siswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Data Siswa</h1>
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto" required>

            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" required>

            <button type="submit">Simpan</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Foto</th>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["nama"]; ?></td>
                        <td><img src="<?php echo $row["foto"]; ?>" alt="Foto" width="100"></td>
                        <td><?php echo $row["kelas"]; ?></td>
                        <td>
                            <a href="index.php?edit=<?php echo $row["id"]; ?>">Edit</a>
                            <a href="index.php?delete=<?php echo $row["id"]; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
