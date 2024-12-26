<?php
include 'koneksi.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';

switch ($aksi) {
    case 'list':
?>
        <div class="row">
            <h2>Data Mahasiswa</h2>
            <div class="col-2">
                <a href="mahasiswa.php?aksi=input" class="btn btn-primary mb-3">Tambah Mahasiswa</a>
            </div>

            <table class="table table-bordered">
                <link rel="stylesheet" href="css/table.css">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Email</th>
                        <th>Prodi</th>-
                        <th>No Telepon</th>
                        <th>Hobi</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $db->prepare("SELECT mahasiswa.*, prodi.nama_prodi FROM mahasiswa INNER JOIN prodi ON prodi.id = mahasiswa.prodi_id");
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $no = 1;

                        // Menampilkan data mahasiswa
                        foreach ($result as $data) {
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($no) ?></td>
                                <td><?= htmlspecialchars($data['nim']) ?></td>
                                <td><?= htmlspecialchars($data['nama_mhs']) ?></td>
                                <td><?= htmlspecialchars($data['tgl_lahir']) ?></td>
                                <td><?= htmlspecialchars($data['jenis_kelamin']) ?></td>
                                <td><?= htmlspecialchars($data['email']) ?></td>
                                <td><?= htmlspecialchars($data['nama_prodi']) ?></td>
                                <td><?= htmlspecialchars($data['nohp']) ?></td>
                                <td><?= htmlspecialchars($data['hobi']) ?></td>
                                <td><?= htmlspecialchars($data['alamat']) ?></td>
                                <td>
                                    <a href="mahasiswa.php?aksi=edit&id=<?= urlencode($data['nim']) ?>" class="btn btn-success">Edit</a>
                                    <a href="proses_mahasiswa.php?proses=delete&nim=<?= urlencode($data['nim']) ?>" class="btn btn-warning" onclick="return confirm('Yakin akan menghapus data?')">Hapus</a>
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
        <div class="container mt-5">
            <link rel="stylesheet" href="css/form.css">
            <h2 class="text-center mb-4">Masukkan Data Mahasiswa</h2>
            <a href="mahasiswa.php?aksi=list" class="btn btn-primary mb-4">Data Mahasiswa</a>
            <form action="proses_mahasiswa.php?proses=insert" method="POST">
                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="number" class="form-control" name="nim" id="nim" required>
                </div>

                <div class="mb-3">
                    <label for="nama_mhs" class="form-label">Nama Mahasiswa</label>
                    <input type="text" class="form-control" name="nama_mhs" id="nama_mhs" required>
                </div>

                <div class="mb-3">
                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" required>
                </div>

                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                        <option value="">--Pilih Jenis Kelamin--</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="mb-3">
                    <label for="nohp" class="form-label">No Telepon</label>
                    <input type="text" class="form-control" name="nohp" id="nohp" required>
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
                    <label for="hobi" class="form-label">Hobi</label>
                    <input type="text" class="form-control" name="hobi" id="hobi" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                </div>

                <button type="submit" name="proses" class="btn btn-danger">Proses</button>
                <button type="reset" class="btn btn-primary">Reset</button>
            </form>
        </div>

        <?php
        break;

    case 'edit':
        try {
            $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
            $stmt->bindParam(':nim', $_GET['id']);
            $stmt->execute();
            $data_mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
            <div class="container mt-5">
                <link rel="stylesheet" href="css/form.css">
                <h2 class="mb-4 text-center">Edit Data Mahasiswa</h2>
                <a href="mahasiswa.php?aksi=list" class="btn btn-primary mb-4">Kembali ke Data Mahasiswa</a>
                <form action="proses_mahasiswa.php?proses=update" method="POST">
                    <input type="hidden" name="nim" value="<?= htmlspecialchars($data_mahasiswa['nim']) ?>">

                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="number" class="form-control" name="nim" id="nim" value="<?= htmlspecialchars($data_mahasiswa['nim']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="nama_mhs" class="form-label">Nama Mahasiswa</label>
                        <input type="text" class="form-control" name="nama_mhs" id="nama_mhs" value="<?= htmlspecialchars($data_mahasiswa['nama_mhs']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" value="<?= htmlspecialchars($data_mahasiswa['tgl_lahir']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki" <?= $data_mahasiswa['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $data_mahasiswa['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($data_mahasiswa['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="nohp" class="form-label">No Telepon</label>
                        <input type="tel" class="form-control" name="nohp" id="nohp" value="<?= htmlspecialchars($data_mahasiswa['nohp']) ?>" required>
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
                        <label for="hobi" class="form-label">Hobi</label>
                        <input type="text" class="form-control" name="hobi" id="hobi" value="<?= htmlspecialchars($data_mahasiswa['hobi']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data_mahasiswa['alamat']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="prodi_id" class="form-label">Program Studi</label>
                    </div>

                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </form>
            </div>

<?php
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        break;
}
?>