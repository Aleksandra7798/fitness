<?php

class BookingDetailDAO
{

    public function __construct()
    {
    }

    // use this in admin.php
    protected function fetchBooking()
    {
        $sql = 'SELECT
          t1.id,
          t1.cid,
          t1.status,
          t1.notes,
          t2.start,
          t2.end,
          t2.type,
          t2.requirement,
          t2.cadre,
          t2.service,
          t2.memo,
          t2.timestamp
        FROM booking AS t1 LEFT JOIN reservation AS t2 ON t1.id = t2.id;';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    protected function fetchBookingByCid($cid)
    {
        $sql = 'SELECT
          t1.id,
          t1.status,
          t2.start,
          t2.end,
          t2.type,
          t2.requirement,
          t2.cadre,
          t2.service,
          t2.memo,
          t2.timestamp
        FROM booking AS t1 LEFT JOIN reservation AS t2 ON t1.id = t2.id
        WHERE t1.cid = ?;';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$cid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    protected function updatePotwierdzona($i)
    {
        $sql = 'UPDATE `booking` SET `status` = ? WHERE `booking`.`id` = ' . $i . ';';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(["POTWIERDZONA"]);
        return $exec;
    }

    protected function updateAnulowana($i)
    {
        $sql = 'UPDATE `booking` SET `status` = ? WHERE `booking`.`id` = ' . $i . ';';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(["ANULOWANA"]);
        return $exec;
    }


    protected function updateBooking($id, $isForConfirmation, $isForCancellation)
    {
        $sql = 'UPDATE `booking` SET `status` = ? WHERE `booking`.`id` = ' . $id . ';';
        $stmt = DB::getInstance()->prepare($sql);
        $updateStatus = [\models\StatusEnum::OCZEKUJACA_STR, \models\StatusEnum::POTWIERDZONA_STR, \models\StatusEnum::ANULOWANA_STR];
        if ($isForConfirmation) {
            $exec = $stmt->execute($updateStatus[1]);
        } else if ($isForCancellation) {
            $exec = $stmt->execute($updateStatus[2]);
        } else {
            $exec = $stmt->execute($updateStatus[0]);
        }
        return $exec;
    }
}


