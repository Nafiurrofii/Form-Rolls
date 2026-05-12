<?php

class Roll {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * GET ALL DATA
     */
    public function getAll($startDate = null, $endDate = null) {
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
                    register AS reg,
                    barcode,
                    keterangan,
                    pic AS user
                FROM rolls";
                
        $params = [];
        if ($startDate && $endDate) {
            $sql .= " WHERE tanggal BETWEEN :start AND :end";
            $params[':start'] = $startDate;
            $params[':end'] = $endDate;
        }

        $sql .= " ORDER BY urut DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * STORE DATA
     */
    public function store($data) {
        $sql = "INSERT INTO rolls (
                    id,
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
                    keterangan,
                    pic
                ) VALUES (
                    UUID(),
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
                    :keterangan,
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
            ':keterangan' => $data['keterangan'],
            ':pic' => $data['pic']
        ]);

        $lastUrut = $this->pdo->lastInsertId();

        // Update register dan barcode otomatis sama dengan urut
        $updateSql = "UPDATE rolls SET register = :val, barcode = :val WHERE urut = :urut";
        $updateStmt = $this->pdo->prepare($updateSql);
        $updateStmt->execute([
            ':val' => $lastUrut,
            ':urut' => $lastUrut
        ]);

        return [
            'id' => $lastUrut
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
                    keterangan = :keterangan,
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
            ':keterangan' => $data['keterangan'],
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
        $stmt->execute([':id' => $id]);
        return true;
    }

}
