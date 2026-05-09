<?php

class Roll {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * GET ALL DATA
     */
    public function getAll() {

        $sql = "SELECT 
                    id,
                    tanggal AS tgl,
                    jam,
                    roll,
                    group_name AS shift,
                    mesin,
                    nama,
                    denier AS dnr,
                    panjang AS pj,
                    lebar AS lb,
                    anyam,
                    berat AS br,
                    trace_code AS trace,
                    register_no AS reg,
                    pic AS user
                FROM rolls
                ORDER BY id DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * STORE DATA
     */
    public function store($data) {

        $sql = "INSERT INTO rolls (
                    tanggal,
                    jam,
                    roll,
                    group_name,
                    mesin,
                    nama,
                    denier,
                    panjang,
                    lebar,
                    anyam,
                    berat,
                    trace_code,
                    pic
                ) VALUES (
                    :tanggal,
                    :jam,
                    :roll,
                    :group_name,
                    :mesin,
                    :nama,
                    :denier,
                    :panjang,
                    :lebar,
                    :anyam,
                    :berat,
                    :trace_code,
                    :pic
                )";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':tanggal' => $data['tanggal'],
            ':jam' => $data['jam'],
            ':roll' => $data['roll'],
            ':group_name' => $data['group_name'],
            ':mesin' => $data['mesin'],
            ':nama' => $data['nama'],
            ':denier' => $data['denier'],
            ':panjang' => $data['panjang'],
            ':lebar' => $data['lebar'],
            ':anyam' => $data['anyam'],
            ':berat' => $data['berat'],
            ':trace_code' => $data['trace_code'],
            ':pic' => $data['pic']
        ]);

        return [
            'id' => $this->pdo->lastInsertId()
        ];
    }

    /**
     * UPDATE DATA
     */
    public function update($id, $data) {

        $sql = "UPDATE rolls SET
                    tanggal = :tanggal,
                    jam = :jam,
                    roll = :roll,
                    group_name = :group_name,
                    mesin = :mesin,
                    nama = :nama,
                    denier = :denier,
                    panjang = :panjang,
                    lebar = :lebar,
                    anyam = :anyam,
                    berat = :berat,
                    trace_code = :trace_code,
                    pic = :pic
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':tanggal' => $data['tanggal'],
            ':jam' => $data['jam'],
            ':roll' => $data['roll'],
            ':group_name' => $data['group_name'],
            ':mesin' => $data['mesin'],
            ':nama' => $data['nama'],
            ':denier' => $data['denier'],
            ':panjang' => $data['panjang'],
            ':lebar' => $data['lebar'],
            ':anyam' => $data['anyam'],
            ':berat' => $data['berat'],
            ':trace_code' => $data['trace_code'],
            ':pic' => $data['pic']
        ]);

        return true;
    }

    /**
     * DELETE DATA
     */
    public function delete($id) {

        $sql = "DELETE FROM rolls WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
