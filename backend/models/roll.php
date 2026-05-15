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

    public function continue($id, $data) {

        $sql = "
            UPDATE rolls
            SET
                jam = :jam,
                roll = :roll,
                group_name = :group_name,
                mesin = :mesin,
                panjang = :panjang,
                lebar = :lebar,
                berat = :berat,
                pic = :pic,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':jam'        => $data['jam'],
            ':roll'       => $data['roll'],
            ':group_name' => $data['group_name'],
            ':mesin'      => $data['mesin'],
            ':panjang'    => $data['panjang'],
            ':lebar'      => $data['lebar'],
            ':berat'      => $data['berat'],
            ':pic'        => $data['pic'],
            ':id'         => $id
        ]);
    }

    public function getChartData($days = 14, $startDate = null, $endDate = null) {
        // If specific range provided, use it
        if ($startDate && $endDate) {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
            $interval = $start->diff($end);
            $days = $interval->days + 1;
            
            // Limit to 90 days for performance
            if ($days > 90) $days = 90;
        } else {
            $days = (int)$days;
            if ($days <= 0) $days = 14;
            if ($days > 90) $days = 90;
            $endDate = date('Y-m-d');
            $startDate = date('Y-m-d', strtotime("-".($days-1)." days"));
        }

        // 1. Summary (Always for today)
        $summary = [
            'hariIni' => 0,
            'kemarin' => 0,
            'bulan' => 0,
            'rata' => 0,
            'mesinAktif' => 0,
            'groupAktif' => 0,
            'shiftAktif' => '-'
        ];

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $firstDayOfMonth = date('Y-m-01');

        $stmt = $this->pdo->query("SELECT 
            SUM(CASE WHEN tanggal = '$today' THEN 1 ELSE 0 END) as hari_ini,
            SUM(CASE WHEN tanggal = '$yesterday' THEN 1 ELSE 0 END) as kemarin,
            SUM(CASE WHEN tanggal >= '$firstDayOfMonth' THEN 1 ELSE 0 END) as bulan
            FROM rolls");
        $counts = $stmt->fetch();
        $summary['hariIni'] = (int)$counts['hari_ini'];
        $summary['kemarin'] = (int)$counts['kemarin'];
        $summary['bulan'] = (int)$counts['bulan'];

        $dayOfMonth = (int)date('j');
        $summary['rata'] = $dayOfMonth > 0 ? round($summary['bulan'] / $dayOfMonth) : 0;

        $stmt = $this->pdo->query("SELECT COUNT(DISTINCT mesin) as m, COUNT(DISTINCT group_name) as g, GROUP_CONCAT(DISTINCT group_name SEPARATOR '/') as s FROM rolls WHERE tanggal = '$today'");
        $active = $stmt->fetch();
        $summary['mesinAktif'] = (int)$active['m'];
        $summary['groupAktif'] = (int)$active['g'];
        $summary['shiftAktif'] = $active['s'] ? $active['s'] : '-';

        // 2. Top Produk (Last 30 days)
        $stmt = $this->pdo->query("SELECT nama, COUNT(*) as total FROM rolls WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY nama ORDER BY total DESC LIMIT 5");
        $topRows = $stmt->fetchAll();
        $colors = ['#1d6fd8', '#0ea5c9', '#10b981', '#f59e0b', '#8b5cf6'];
        $topProduk = [];
        foreach ($topRows as $i => $row) {
            $topProduk[] = [
                'nama' => $row['nama'],
                'total' => (int)$row['total'],
                'color' => isset($colors[$i]) ? $colors[$i] : '#94a3b8'
            ];
        }

        // 3. Chart Data
        $chartData = [];
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("$startDate +$i days"));
            if ($date > $endDate) break;
            $chartData[$date] = [
                'tanggal' => $date,
                'total' => 0,
                'items' => []
            ];
        }

        $stmt = $this->pdo->prepare("SELECT tanggal, nama, roll, mesin, group_name, jam, pic FROM rolls WHERE tanggal BETWEEN :start AND :end ORDER BY tanggal ASC, jam ASC");
        $stmt->execute([':start' => $startDate, ':end' => $endDate]);
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $t = $row['tanggal'];
            if (isset($chartData[$t])) {
                $chartData[$t]['total']++;
                $chartData[$t]['items'][] = [
                    'nama' => $row['nama'],
                    'roll' => $row['roll'],
                    'mesin' => $row['mesin'],
                    'group' => $row['group_name'],
                    'jam' => date('H:i', strtotime($row['jam'])),
                    'pic' => $row['pic']
                ];
            }
        }

        return [
            'status' => 'success',
            'summary' => $summary,
            'topProduk' => $topProduk,
            'data' => array_values($chartData)
        ];
    }

}
