<?php
include 'koneksi.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';

switch ($aksi) {
    case 'list':
?>
        <div class="row">
            <h2>Data Dosen</h2>
            <div class="col-2">
                <a href="dosen.php?p=dosen&aksi=input" class="btn btn-primary mb-3">Tambah Dosen</a>
            </div>

            <table class="table table-bordered">
                <link rel="stylesheet" href="css/table.css">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama Dosen</th>
                        <th>Email</th>
                        <th>Prodi</th>
                        <th>No Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $db->prepare("SELECT dosen.*, prodi.nama_prodi FROM dosen INNER JOIN prodi ON prodi.id = dosen.prodi_id");
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $no = 1;

                        // Menampilkan data dosen
                        foreach ($result as $data) {
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($no) ?></td> <!-- Menangani output No -->
                                <td><?= htmlspecialchars($data['nip']) ?></td>
                                <td><?= htmlspecialchars($data['nama_dosen']) ?></td>
                                <td><?= htmlspecialchars($data['email']) ?></td>
                                <td><?= htmlspecialchars($data['nama_prodi']) ?></td>
                                <td><?= htmlspecialchars($data['notelp']) ?></td>
                                <td><?= htmlspecialchars($data['alamat']) ?></td>
                                <td>
                                    <a href="dosen.php?p=dosen&aksi=edit&id=<?= urlencode($data['id']) ?>" class="btn btn-success">Edit</a>
                                    <a href="proses_dosen.php?proses=delete&id=<?= urlencode($data['id']) ?>" class="btn btn-warning" onclick="return confirm('Yakin akan menghapus data?')">Hapus</a>
                                </td>
                            </tr>
                    <?php
                            $no++; // Increment nomor urut
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='8'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    <?php
        break;

    case 'input':
    ?>
        <div class="container">
            <link rel="stylesheet" href="css/form.css">
            <h2>Masukkan Data Dosen</h2>
            <a href="dosen.php?p=list" class="btn btn-primary mb-3">Data Dosen</a>
            <form action="proses_dosen.php?proses=insert" method="POST">
                <div class="mb-3">
                    <label for="nip">NIP</label>
                    <input type="number" class="form-control" name="nip" id="nip" required>
                </div>

                <div class="mb-3">
                    <label for="nama_dosen">Nama Dosen</label>
                    <input type="text" class="form-control" name="nama_dosen" id="nama_dosen" required>
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="prodi_id">Program Studi</label>
                    <select name="prodi_id" class="form-select" required>
                        <option value="">--PILIH PRODI--</option>
                        <?php
                        try {
                            $stmt = $db->prepare("SELECT * FROM prodi");
                            $stmt->execute();
                            $prodi = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($prodi as $data_prodi) {
                                echo "<option value=\"" . $data_prodi['id'] . "\">" . $data_prodi['nama_prodi'] . "</option>";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="notelp">Nomor Telepon</label>
                    <input type="number" class="form-control" name="notelp" id="notelp" required>
                </div>

                <div class="mb-3">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" class="form-control" id="alamat" required></textarea>
                </div>

                <button type="submit" name="proses" class="btn btn-danger">Proses</button>
                <button type="reset" class="btn btn-primary">Reset</button>
            </form>
        </div>

        <?php
        break;

    case 'edit':
        try {
            $stmt = $db->prepare("SELECT * FROM dosen WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();
            $data_dosen = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
         <div class="container">
         <link rel="stylesheet" href="css/form.css">
            <div class="row">
                <div class="col-7">
                    <h2>Edit Data Dosen</h2>
                    <a href="dosen.php?p=list" class="btn btn-primary mb-3">Data Dosen</a>
                    <form action="proses_dosen.php?proses=update" method="POST">
                        <input type="hidden" name="id" value="<?= $data_dosen['id'] ?>">

                        <div class="mb-3">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" name="nip" id="nip" value="<?= htmlspecialchars($data_dosen['nip']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama_dosen">Nama Dosen</label>
                            <input type="text" class="form-control" name="nama_dosen" id="nama_dosen" value="<?= htmlspecialchars($data_dosen['nama_dosen']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($data_dosen['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="prodi_id">Program Studi</label>
                            <select name="prodi_id" class="form-select" required>
                                <?php
                                $stmt = $db->prepare("SELECT * FROM prodi");
                                $stmt->execute();
                                $prodi = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($prodi as $data_prodi) {
                                    $selected = $data_prodi['id'] == $data_dosen['prodi_id'] ? 'selected' : '';
                                    echo "<option value=" . $data_prodi['id'] . " $selected>" . $data_prodi['nama_prodi'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notelp">Nomor Telepon</label>
                            <input type="number" class="form-control" name="notelp" id="notelp" value="<?= htmlspecialchars($data_dosen['notelp']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" class="form-control" id="alamat" required><?= htmlspecialchars($data_dosen['alamat']) ?></textarea>
                        </div>

                        <button type="submit" name="proses" class="btn btn-danger">Update</button>
                        <button type="reset" class="btn btn-primary">Reset</button>
                    </form>
                </div>
            </div>
<?php
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        break;
}
?>