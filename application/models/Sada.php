<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Sada extends CI_Model{



  public function __construct()

  {

    parent::__construct();

    //Codeigniter : Write Less Do More

  }




  public function getTopSku($filter)
  {
    $startDate = $filter['startDate'];
    $endDate = $filter['endDate'];
    $startDateMonthAgo = $filter['startDateMonthAgo'];
    $endDateMonthAgo = $filter['endDateMonthAgo'];
    $volume = $this->db->select(['id_produk ', 'SUM(qty) monthVolume'])
    ->from('sada_produk_terjual')
    ->where("CAST(tgl AS DATE) BETWEEN '$startDate' AND '$endDate'")
    ->group_by('id_produk')
    ->get()
    ->result();
    $volumeMonthAgo = $this->db->select(['id_produk ', 'SUM(qty) monthAgoVolume'])
    ->from('sada_produk_terjual')
    ->where("CAST(tgl AS DATE) BETWEEN '$startDateMonthAgo' AND '$endDateMonthAgo'")
    ->group_by('id_produk')
    ->get()
    ->result();


    $info = $this->db->select(['id_produk','(select sd_kat.price from sada_produk p inner join sada_kategori sd_kat on p.id_kategori = sd_kat.id where p.id_produk = pt.id_produk) price',
      '(select nama_produk from sada_produk p where p.id_produk = pt.id_produk) namaProduk',
      '(select nama from sada_kategori k inner join sada_produk p  on p.id_kategori = k.id where pt.id_produk = p.id_produk) segmen',
      ])
    ->from('sada_produk_terjual pt')
    ->group_by('id_produk')
//            ->order_by('monthVolume', 'desc')
    ->get()
    ->result();
    $merged = array_merge($info, $volume, $volumeMonthAgo);

    $response = [
    ];
    foreach ($merged as $key => $value) {
      if (isset($value->id_produk) && isset($value->namaProduk) && isset($value->segmen) && isset($value->price)) {
        $response[$value->id_produk] = [
        'idProduk' => $value->id_produk,
        'namaProduk' => $value->namaProduk,
        'segmen' => $value->segmen,
        'price'=>'Rp '.number_format($value->price,0,",",".").',-'
        ];
      }
      if (isset($value->monthVolume)) {
        $response[$value->id_produk]['monthVolume']= $value->monthVolume;

        $response[$value->id_produk]['prices']= $value->monthVolume * $value->price;

        $response[$value->id_produk]['pricevvv']= $value->price;
      }
      if (isset($value->monthAgoVolume)) {
        $response[$value->id_produk]['monthAgoVolume']= $value->monthAgoVolume;
      }
    }
    return $response;
//        return array_merge($info, [  $volume, $volumeMonthAgo]);
//            $mergedArray = array_push($info,[$volume,$volumeMonthAgo]);
//            return $mergedArray;
  }


  public function getTopBA($startDate,$endDate,$startDateMonthAgo,$endDateMonthAgo)
  {

    $sql_volume = "SELECT
    `id_user`,
    SUM(qty) monthVolume,
  -- sdkat.price, 
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 1
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_bc_prtj,
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 2
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_bti_prtj,
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 3
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_rusk_prtj,
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 4
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_pudding_prtj,
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 5
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_others_prtj,

  (SELECT price from sada_kategori where id = 1) as harga_bc,

  (SELECT price from sada_kategori where id = 2) as harga_bti,

  (SELECT price from sada_kategori where id = 3) as harga_rusk,

  (SELECT price from sada_kategori where id = 4) as harga_pudding,

  (SELECT price from sada_kategori where id = 5) as harga_others,
  (
  SELECT DISTINCT
  SUM(target_toko.target)
  FROM
  sada_toko toko
  INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
  WHERE
  toko.id_toko = sada_produk_terjual.id_toko
  ) AS target_ba
  FROM
  `sada_produk_terjual`
  INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
  WHERE
  CAST(tgl AS DATE) BETWEEN '$startDate'
  AND '$endDate'
  GROUP BY
  `id_user`
  ";
  // echo $sql_volume;
  $sql_volumeAgo = "SELECT
  `id_user`,
  SUM(qty) monthVolume,
  -- sdkat.price, 
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 1
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_bc_prtj,
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 2
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_bti_prtj,
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 3
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_rusk_prtj,
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 4
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_pudding_prtj,
  (
  SELECT
  SUM(qty)
  FROM
  sada_produk_terjual as ss
  INNER JOIN `sada_produk` as s ON `ss`.`id_produk` = `s`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
  WHERE
  sdkats.id = 5
  AND ss.id_user = sada_produk_terjual.id_user
  ) AS qty_others_prtj,

  (SELECT price from sada_kategori where id = 1) as harga_bc,

  (SELECT price from sada_kategori where id = 2) as harga_bti,

  (SELECT price from sada_kategori where id = 3) as harga_rusk,

  (SELECT price from sada_kategori where id = 4) as harga_pudding,

  (SELECT price from sada_kategori where id = 5) as harga_others,
  (
  SELECT DISTINCT
  SUM(target_toko.target)
  FROM
  sada_toko toko
  INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
  WHERE
  toko.id_toko = sada_produk_terjual.id_toko
  ) AS target_ba
  FROM
  `sada_produk_terjual`
  INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
  INNER JOIN `sada_kategori` AS sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
  WHERE
  CAST(tgl AS DATE) BETWEEN '$startDateMonthAgo'
  AND '$endDateMonthAgo'
  GROUP BY
  `id_user`
  ";
  $volume = $this->db->query($sql_volume)->result();   
    // $this->db->select(['id_user ', 'SUM(qty) monthVolume',  '(SELECT SUM(price) from sada_kategori as kat where kat.id_kategori=sada_produk.id_kategori) as price_kat'])
    // ->from('sada_produk_terjual')
    // ->join('sada_produk','sada_produk_terjual.id_produk = sada_produk.id_produk','inner')
    // ->where("CAST(tgl AS DATE) BETWEEN '$startDate' AND '$endDate'")
    // ->group_by('id_user')
    // ->get()
    // ->result();
// echo $sql_volumeAgo;
  $volumeMonthAgo = $this->db->query($sql_volumeAgo)->result();
// $this->db->select(['id_user ', 'SUM(qty) monthAgoVolume'])
// ->from('sada_produk_terjual')
// ->where("CAST(tgl AS DATE) BETWEEN '$startDateMonthAgo' AND '$endDateMonthAgo'")
// ->group_by('id_user')
// ->get()
// ->result();

    // $topBA = $this->db->select('(select cab.nama from sada_cabang cab where cab.id_cabang = sada_kota.id_cabang)',
    //                             '(select user.nama from sada_user)'
    //   )
  $topBA = $this->db->query('SELECT
    id_user,
  -- sada_produk.price,
  sdkat.price,
  (
  SELECT
  cab.nama
  FROM
  sada_cabang cab
  WHERE
  cab.id_cabang = sada_kota.id_cabang
  ) AS nama_cabang,
  (
  SELECT
  USER .nama
  FROM
  sada_user USER
  LEFT JOIN sada_tl_in_kota ON USER .id_user = sada_tl_in_kota.id_user
  WHERE
  sada_tl_in_kota.id_kota = sada_kota.id_kota
  ) AS nama_tl,
  (
  SELECT DISTINCT
  USER .nama
  FROM
  sada_user USER
  WHERE
  id_user = sada_produk_terjual.id_user
  ) AS nama_ba,
  -- (
  -- SELECT
  -- sum(sada_target_user.target)
  -- FROM
  -- sada_target_user
  -- LEFT JOIN sada_user ON sada_user.id_user = sada_target_user.id_user
  -- WHERE
  -- sada_target_user.id_user = (
  -- SELECT DISTINCT
  -- prt.id_user
  -- FROM
  -- sada_produk_terjual AS prt
  -- WHERE
  -- prt.id_user = sada_produk_terjual.id_user
  -- )
  -- ) AS target_ba_user
  (
  SELECT DISTINCT
  SUM(target_toko.target)
  FROM
  sada_toko toko
  INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
  WHERE
  toko.id_toko = sada_produk_terjual.id_toko
  ) AS target_ba
  FROM
  sada_produk_terjual
  
  INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
  INNER JOIN `sada_kategori` as sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
  INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
  INNER JOIN sada_kota ON sada_toko.id_kota = sada_kota.id_kota
  GROUP BY
  id_user')
  ->result();


  $merged = array_merge($topBA, $volume, $volumeMonthAgo);
  $response = [
  ];
  foreach ($merged as $value) {
    if (isset($value->nama_cabang) && isset($value->nama_tl) && isset($value->nama_ba) && isset($value->target_ba) && isset($value->price)) {
      $response[$value->id_user] = [
      'cabang'=>$value->nama_cabang,
      'nama_tl'=>$value->nama_tl,
      'nama_ba'=>$value->nama_ba,
      'target_ba'=>$value->target_ba,
      'price'=>'Rp '.number_format($value->price,0,",",".").',-'
      ];
    }

      

    if (isset($value->monthVolume)) {
      $response[$value->id_user]['monthVolume'] = $value->monthVolume;
      $response[$value->id_user]['qty_bc_prtj'] = $value->qty_bc_prtj;
      $response[$value->id_user]['qty_bti_prtj'] = $value->qty_bti_prtj;
      $response[$value->id_user]['qty_rusk_prtj'] = $value->qty_rusk_prtj;
      $response[$value->id_user]['qty_pudding_prtj'] = $value->qty_pudding_prtj;
      $response[$value->id_user]['qty_others_prtj'] = $value->qty_others_prtj;
      $response[$value->id_user]['harga_bc'] = $value->harga_bc;
      $response[$value->id_user]['harga_bti'] = $value->harga_bti;
      $response[$value->id_user]['harga_rusk'] = $value->harga_rusk;
      $response[$value->id_user]['harga_pudding'] = $value->harga_pudding;
      $response[$value->id_user]['harga_others'] = $value->harga_others;
    }
    if (isset($value->monthAgoVolume)) {
      $response[$value->id_user]['monthAgoVolume'] = $value->monthAgoVolume;
      $response[$value->id_user]['qty_bc_prtj'] = $value->qty_bc_prtj;
      $response[$value->id_user]['qty_bti_prtj'] = $value->qty_bti_prtj;
      $response[$value->id_user]['qty_rusk_prtj'] = $value->qty_rusk_prtj;
      $response[$value->id_user]['qty_pudding_prtj'] = $value->qty_pudding_prtj;
      $response[$value->id_user]['qty_others_prtj'] = $value->qty_others_prtj;
      $response[$value->id_user]['harga_bc'] = $value->harga_bc;
      $response[$value->id_user]['harga_bti'] = $value->harga_bti;
      $response[$value->id_user]['harga_rusk'] = $value->harga_rusk;
      $response[$value->id_user]['harga_pudding'] = $value->harga_pudding;
      $response[$value->id_user]['harga_others'] = $value->harga_others;
    }
  }
  return $response;
}

public function getTopCabang($startDate,$endDate,$startDateMonthAgo,$endDateMonthAgo)
{
  $sql_volume = "SELECT
  `id_toko`,
  SUM(qty) monthVolume,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 1
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_bc_prtj,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 2
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_bti_prtj,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 3
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_rusk_prtj,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 4
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_pudding_prtj,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 5
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_others_prtj,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 1
  ) AS harga_bc,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 2
  ) AS harga_bti,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 3
  ) AS harga_rusk,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 4
  ) AS harga_pudding,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 5
  ) AS harga_others,
  (
    SELECT DISTINCT
      SUM(target_toko.target)
    FROM
      sada_toko toko
    INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
    WHERE
      toko.id_toko = sada_produk_terjual.id_toko
  ) AS target_ba
FROM
  `sada_produk_terjual`
INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
INNER JOIN `sada_kategori` AS sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
WHERE
  CAST(tgl AS DATE) BETWEEN '$startDate'
  AND '$endDate'
  ORDER BY id_toko
  ";
  $volume = $this->db->query($sql_volume)->result();
  // $volume = $this->db->select(['id_toko ', 'SUM(qty) monthVolume'])
  // ->from('sada_produk_terjual')
  // ->where("CAST(tgl AS DATE) BETWEEN '$startDate' AND '$endDate'")
  // ->group_by('id_toko')
  // ->get()
  // ->result();
  $sql_volumeAgo = "SELECT
  `id_toko`,
  SUM(qty) monthVolume,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 1
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_bc_prtj,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 2
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_bti_prtj,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 3
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_rusk_prtj,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 4
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_pudding_prtj,
  (
    SELECT
      SUM(qty)
    FROM
      sada_produk_terjual AS ss
    INNER JOIN `sada_produk` AS s ON `ss`.`id_produk` = `s`.`id_produk`
    INNER JOIN `sada_kategori` AS sdkats ON `s`.`id_kategori` = `sdkats`.`id`
    WHERE
      sdkats.id = 5
    AND ss.id_toko = sada_produk_terjual.id_toko
  ) AS qty_others_prtj,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 1
  ) AS harga_bc,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 2
  ) AS harga_bti,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 3
  ) AS harga_rusk,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 4
  ) AS harga_pudding,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 5
  ) AS harga_others,
  (
    SELECT DISTINCT
      SUM(target_toko.target)
    FROM
      sada_toko toko
    INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
    WHERE
      toko.id_toko = sada_produk_terjual.id_toko
  ) AS target_ba
FROM
  `sada_produk_terjual`
INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
INNER JOIN `sada_kategori` AS sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
WHERE
  CAST(tgl AS DATE) BETWEEN '$startDateMonthAgo'
  AND '$endDateMonthAgo'
  GROUP BY
  `id_toko`
  ";
  // echo $sql_volumeAgo;
  $volumeMonthAgo = $this->db->query($sql_volumeAgo)->result();
  // $volumeMonthAgo = $this->db->select(['id_toko ', 'SUM(qty) monthAgoVolume'])
  // ->from('sada_produk_terjual')
  // ->where("CAST(tgl AS DATE) BETWEEN '$startDateMonthAgo' AND '$endDateMonthAgo'")
  // ->group_by('id_toko')
  // ->get()
  // ->result();

  $sql = "SELECT
  sada_produk_terjual.id_toko,

  -- sada_produk.price,
  sdkat.price,
  (
  SELECT
  cab.pic
  FROM
  sada_cabang cab
  WHERE
  cab.id_cabang = sada_kota.id_cabang
  ) AS pic_cabang,
  -- (
  -- SELECT
  -- cab.target
  -- FROM
  -- sada_cabang cab
  -- WHERE
  -- cab.id_cabang = sada_kota.id_cabang
  -- ) AS target_cabang,
  (
  SELECT DISTINCT
  SUM(target_toko.target)
  FROM
  sada_toko toko
  INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
  WHERE
  toko.id_toko = sada_produk_terjual.id_toko
  ) AS target_cabang,
  (SELECT
  COUNT(DISTINCT user_temp.id_user)
  FROM
  sada_tokoinuser_temp user_temp
  INNER JOIN sada_toko as tok ON tok.id_toko = user_temp.id_toko
  INNER JOIN sada_kota as kot ON tok.id_kota = kot.id_kota
  INNER JOIN sada_cabang as cab ON kot.id_cabang = cab.id_cabang
  WHERE
  user_temp.id_toko = sada_produk_terjual.id_toko
) AS jml_ba_cabang,
(SELECT
  COUNT(tok.id_kota)
  FROM
  sada_toko tok
  INNER JOIN sada_kota as kot ON tok.id_kota = kot.id_kota
  INNER JOIN sada_cabang as cab ON kot.id_cabang = cab.id_cabang
  WHERE
  cab.id_cabang = sada_cabang.id_cabang
) AS jml_toko_cabang

FROM
sada_produk_terjual
INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
INNER JOIN sada_kota ON sada_toko.id_kota = sada_kota.id_kota
INNER JOIN sada_cabang ON sada_kota.id_cabang = sada_cabang.id_cabang
INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
INNER JOIN `sada_kategori` as sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
GROUP BY
sada_produk_terjual.id_toko
";
$topCabang = $this->db->query($sql)->result();

$merged = array_merge($topCabang, $volume, $volumeMonthAgo);
$response = [
];

foreach ($merged as $value) {
  if (isset($value->pic_cabang) && isset($value->target_cabang) && isset($value->jml_ba_cabang) && isset($value->jml_toko_cabang)) {
    $response[$value->id_toko] = [
    'pic_cabang'=>$value->pic_cabang,
    'target_cabang'=>$value->target_cabang,
    'jml_ba_cabang'=>$value->jml_ba_cabang,
    'jml_toko_cabang'=>$value->jml_toko_cabang,
    'price'=>'Rp '.number_format($value->price,0,",",".").',-'
    ];
  }



  if (isset($value->monthVolume)) {
    $response[$value->id_toko]['monthVolume']= $value->monthVolume;
      $response[$value->id_toko]['qty_bc_prtj'] = $value->qty_bc_prtj;
      $response[$value->id_toko]['qty_bti_prtj'] = $value->qty_bti_prtj;
      $response[$value->id_toko]['qty_rusk_prtj'] = $value->qty_rusk_prtj;
      $response[$value->id_toko]['qty_pudding_prtj'] = $value->qty_pudding_prtj;
      $response[$value->id_toko]['qty_others_prtj'] = $value->qty_others_prtj;
      $response[$value->id_toko]['harga_bc'] = $value->harga_bc;
      $response[$value->id_toko]['harga_bti'] = $value->harga_bti;
      $response[$value->id_toko]['harga_rusk'] = $value->harga_rusk;
      $response[$value->id_toko]['harga_pudding'] = $value->harga_pudding;
      $response[$value->id_toko]['harga_others'] = $value->harga_others;
  }
  if (isset($value->monthAgoVolume)) {
    $response[$value->id_toko]['monthAgoVolume']= $value->monthAgoVolume;
      $response[$value->id_toko]['qty_bc_prtj'] = $value->qty_bc_prtj;
      $response[$value->id_toko]['qty_bti_prtj'] = $value->qty_bti_prtj;
      $response[$value->id_toko]['qty_rusk_prtj'] = $value->qty_rusk_prtj;
      $response[$value->id_toko]['qty_pudding_prtj'] = $value->qty_pudding_prtj;
      $response[$value->id_toko]['qty_others_prtj'] = $value->qty_others_prtj;
      $response[$value->id_toko]['harga_bc'] = $value->harga_bc;
      $response[$value->id_toko]['harga_bti'] = $value->harga_bti;
      $response[$value->id_toko]['harga_rusk'] = $value->harga_rusk;
      $response[$value->id_toko]['harga_pudding'] = $value->harga_pudding;
      $response[$value->id_toko]['harga_others'] = $value->harga_others;
  }
}
return $response;
}
public function getTopAccount($startDate,$endDate,$startDateMonthAgo,$endDateMonthAgo)
{
  $sql_volume = "SELECT
  (
    SELECT DISTINCT
      sd_account_temp.id_account
    FROM
      sada_account_temp sd_account_temp
    WHERE
      sd_account_temp.id_toko = sada_produk_terjual.id_toko
  ) AS id_toko,
  SUM(qty) monthVolume,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '1'
  ) AS qty_bc_prtj,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '2'
  ) AS qty_bti_prtj,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '3'
  ) AS qty_rusk_prtj,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '4'
  ) AS qty_pudding_prtj,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '5'
  ) AS qty_others_prtj,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 1
  ) AS harga_bc,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 2
  ) AS harga_bti,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 3
  ) AS harga_rusk,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 4
  ) AS harga_pudding,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 5
  ) AS harga_others,
  (
    SELECT DISTINCT
      SUM(target_toko.target)
    FROM
      sada_toko toko
    INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
    WHERE
      toko.id_toko = sada_produk_terjual.id_toko
  ) AS target_ba
FROM
  `sada_produk_terjual`
INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
INNER JOIN sada_account_temp ON sada_account_temp.id_toko = sada_toko.id_toko
INNER JOIN `sada_kategori` AS sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
WHERE
  CAST(tgl AS DATE) BETWEEN '$startDate'
AND '$endDate'
GROUP BY
  (
    SELECT DISTINCT
      sd_account_temp.id_account
    FROM
      sada_account_temp sd_account_temp
    WHERE
      sd_account_temp.id_toko = sada_produk_terjual.id_toko
  )
  ";
  $volume = $this->db->query($sql_volume)->result();
  // $volume = $this->db->select(['id_toko ', 'SUM(qty) monthVolume'])
  // ->from('sada_produk_terjual')
  // ->where("CAST(tgl AS DATE) BETWEEN '$startDate' AND '$endDate'")
  // ->group_by('id_toko')
  // ->get()
  // ->result();
  $sql_volumeAgo = "SELECT
  (
    SELECT DISTINCT
      sd_account_temp.id_account
    FROM
      sada_account_temp sd_account_temp
    WHERE
      sd_account_temp.id_toko = sada_produk_terjual.id_toko
  ) AS id_toko,
  SUM(qty) monthVolume,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '1'
  ) AS qty_bc_prtj,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '2'
  ) AS qty_bti_prtj,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '3'
  ) AS qty_rusk_prtj,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '4'
  ) AS qty_pudding_prtj,
  (
    SELECT
      SUM(sd.qty)
    FROM
      `sada_produk_terjual` sd
    INNER JOIN sada_produk spk ON sd.id_produk = spk.id_produk
    INNER JOIN sada_kategori kat ON kat.id = spk.id_kategori
    WHERE
      sd.id_toko IN (
        SELECT DISTINCT
          sada_account_tempo.id_toko
        FROM
          sada_produk_terjual
        INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
        INNER JOIN sada_account_temp sada_account_tempo ON sada_account_tempo.id_toko = sada_toko.id_toko
        WHERE
          sada_account_tempo.id_account = sada_account_temp.id_account
      )
    AND kat.id = '5'
  ) AS qty_others_prtj,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 1
  ) AS harga_bc,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 2
  ) AS harga_bti,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 3
  ) AS harga_rusk,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 4
  ) AS harga_pudding,
  (
    SELECT
      price
    FROM
      sada_kategori
    WHERE
      id = 5
  ) AS harga_others,
  (
    SELECT DISTINCT
      SUM(target_toko.target)
    FROM
      sada_toko toko
    INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
    WHERE
      toko.id_toko = sada_produk_terjual.id_toko
  ) AS target_ba
FROM
  `sada_produk_terjual`
INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
INNER JOIN sada_toko ON sada_produk_terjual.id_toko = sada_toko.id_toko
INNER JOIN sada_account_temp ON sada_account_temp.id_toko = sada_toko.id_toko
INNER JOIN `sada_kategori` AS sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
WHERE
  CAST(tgl AS DATE) BETWEEN '$startDateMonthAgo'
AND '$endDateMonthAgo'
GROUP BY
  (
    SELECT DISTINCT
      sd_account_temp.id_account
    FROM
      sada_account_temp sd_account_temp
    WHERE
      sd_account_temp.id_toko = sada_produk_terjual.id_toko
  )
  ";
  // echo $sql_volumeAgo;
  $volumeMonthAgo = $this->db->query($sql_volumeAgo)->result();
  // echo $sql_volumeAgo;
  // $volumeMonthAgo = $this->db->query($sql_volumeAgo)->result();
  // $volume = $this->db->select(['(
  //   SELECT DISTINCT
  //   sd_account_temp.id_account
  //   FROM
  //   sada_account_temp sd_account_temp
  //   WHERE
  //   sd_account_temp.id_toko = sada_produk_terjual.id_toko
  //   ) AS id_toko ', 'SUM(qty) monthVolume'])
  // ->from('sada_produk_terjual')
  // ->where("CAST(tgl AS DATE) BETWEEN '$startDate' AND '$endDate'")
  // ->group_by('(SELECT DISTINCT
  //   sd_account_temp.id_account
  //   FROM
  //   sada_account_temp sd_account_temp
  //   WHERE
  //   sd_account_temp.id_toko = sada_produk_terjual.id_toko
  //   )')
  // ->get()
  // ->result();
  // $volumeMonthAgo = $this->db->select(['(
  //   SELECT DISTINCT
  //   sd_account_temp.id_account
  //   FROM
  //   sada_account_temp sd_account_temp
  //   WHERE
  //   sd_account_temp.id_toko = sada_produk_terjual.id_toko
  //   ) AS id_toko ', 'SUM(qty) monthAgoVolume'])
  // ->from('sada_produk_terjual')
  // ->where("CAST(tgl AS DATE) BETWEEN '$startDateMonthAgo' AND '$endDateMonthAgo'")
  // ->group_by('(SELECT DISTINCT
  //   sd_account_temp.id_account
  //   FROM
  //   sada_account_temp sd_account_temp
  //   WHERE
  //   sd_account_temp.id_toko = sada_produk_terjual.id_toko
  //   )')
  // ->get()
  // ->result();
// $volume = $this->db->select(['id_toko ', 'SUM(qty) monthVolume'])
//     ->from('sada_produk_terjual')
//     ->where("CAST(tgl AS DATE) BETWEEN '$startDate' AND '$endDate'")
//     ->group_by('id_toko')
//     ->get()
//     ->result();
//     $volumeMonthAgo = $this->db->select(['id_toko ', 'SUM(qty) monthAgoVolume'])
//     ->from('sada_produk_terjual')
//     ->where("CAST(tgl AS DATE) BETWEEN '$startDateMonthAgo' AND '$endDateMonthAgo'")
//     ->group_by('id_toko')
//     ->get()
//     ->result();

  $sql = "SELECT DISTINCT
-- --   (id_toko),
  -- sada_produk.price,
  sdkat.price,
  (
  SELECT DISTINCT
  sd_account_temp.id_account
  FROM
  sada_account_temp sd_account_temp
  WHERE
  sd_account_temp.id_toko = sada_produk_terjual.id_toko
  ) AS id_toko,

  (
  SELECT DISTINCT
  nama_account
  FROM
  sada_account_temp sd_account_temp
  WHERE
  sd_account_temp.id_toko = sada_produk_terjual.id_toko
  ) AS nama_account_temp,
  (
  SELECT
  (
  SELECT
  COUNT(id_account)
  FROM
  sada_account_temp tmp
  WHERE
  tmp.id_account = sada_account_temp.id_account
  )
  FROM
  sada_account_temp
  WHERE
  sada_account_temp.id_toko = sada_produk_terjual.id_toko
  ) AS jml_store_account,
  -- (
  --   SELECT
  --     (
  --       SELECT DISTINCT
  --         target
  --       FROM
  --         sada_account_temp tmp
  --       WHERE
  --         tmp.id_account = sada_account_temp.id_account
  --     )
  --   FROM
  --     sada_account_temp
  --   WHERE
  --     sada_account_temp.id_toko = sada_produk_terjual.id_toko
  -- ) AS target
  (
  SELECT DISTINCT
  SUM(target_toko.target)
  FROM
  sada_toko toko
  INNER JOIN sada_target target_toko ON toko.id_toko = target_toko.id_toko
  WHERE
  toko.id_toko = sada_produk_terjual.id_toko
  ) AS target
  FROM
  sada_produk_terjual
-- INNER JOIN sada_produk ON sada_produk_terjual.id_produk = sada_produk.id_produk
INNER JOIN `sada_produk` ON `sada_produk_terjual`.`id_produk` = `sada_produk`.`id_produk`
INNER JOIN `sada_kategori` as sdkat ON `sada_produk`.`id_kategori` = `sdkat`.`id`
ORDER BY
id_toko
";
$topAccount = $this->db->query($sql)->result();

$merged = array_merge($topAccount, $volume, $volumeMonthAgo);
$response = [
];

foreach ($merged as $value) {
  if (isset($value->nama_account_temp) && isset($value->jml_store_account) && isset($value->target)) {
    $response[$value->id_toko] = [
    'nama_account_temp'=>$value->nama_account_temp,
    'jml_store_account'=>$value->jml_store_account,
    'target'=>$value->target,
    'price'=>'Rp '.number_format($value->price,0,",",".").',-'
    ];
  }



  if (isset($value->monthVolume)) {
    $response[$value->id_toko]['monthVolume']= $value->monthVolume;

      $response[$value->id_toko]['qty_bc_prtj'] = $value->qty_bc_prtj;
      $response[$value->id_toko]['qty_bti_prtj'] = $value->qty_bti_prtj;
      $response[$value->id_toko]['qty_rusk_prtj'] = $value->qty_rusk_prtj;
      $response[$value->id_toko]['qty_pudding_prtj'] = $value->qty_pudding_prtj;
      $response[$value->id_toko]['qty_others_prtj'] = $value->qty_others_prtj;
      $response[$value->id_toko]['harga_bc'] = $value->harga_bc;
      $response[$value->id_toko]['harga_bti'] = $value->harga_bti;
      $response[$value->id_toko]['harga_rusk'] = $value->harga_rusk;
      $response[$value->id_toko]['harga_pudding'] = $value->harga_pudding;
      $response[$value->id_toko]['harga_others'] = $value->harga_others;
  }
  if (isset($value->monthAgoVolume)) {
    $response[$value->id_toko]['monthAgoVolume']= $value->monthAgoVolume;

      $response[$value->id_toko]['qty_bc_prtj'] = $value->qty_bc_prtj;
      $response[$value->id_toko]['qty_bti_prtj'] = $value->qty_bti_prtj;
      $response[$value->id_toko]['qty_rusk_prtj'] = $value->qty_rusk_prtj;
      $response[$value->id_toko]['qty_pudding_prtj'] = $value->qty_pudding_prtj;
      $response[$value->id_toko]['qty_others_prtj'] = $value->qty_others_prtj;
      $response[$value->id_toko]['harga_bc'] = $value->harga_bc;
      $response[$value->id_toko]['harga_bti'] = $value->harga_bti;
      $response[$value->id_toko]['harga_rusk'] = $value->harga_rusk;
      $response[$value->id_toko]['harga_pudding'] = $value->harga_pudding;
      $response[$value->id_toko]['harga_others'] = $value->harga_others;
  }
}
return $response;
}
function _getLoginMobile($dataLogin)

{

  $login['nik']       = htmlentities($dataLogin['Nik']);

  $login['password']  = md5($dataLogin['password']);

  $toko['store_id']   = htmlentities($dataLogin['Store_id']);





  $query_toko = $this->db->get_where("sada_toko",$toko);

    if ($query_toko->num_rows()==1) { //CEK TOKO ADA APA ENGGAK

      $row_toko = $query_toko->row();

      $toko_id = $row_toko->id_toko;



      if ($row_toko->status=="Y") { //CEK TOKO AKTIF ATAU TIDAK

        $query = $this->db->get_where("sada_user",$login);

          if ($query->num_rows()==1) { //CEK USER ADA ATAU TIDAK

            $row = $query->row();

            $user_id = $row->id_user;

            $checkAssignedStore = $this->db->select('id_toko')->from('sada_tokoinuser')->where('id_user',$user_id)->get();

            $first = $checkAssignedStore->first_row();

            

            if ($row->status=="N") { //CEK USER AKTIF ATAU ENGGAK



              $response = array(

                'Success' => false,

                'Info' => 'User Sudah Tidak Aktif');

              $this->output

              ->set_status_header(201)

              ->set_content_type('application/json', 'utf-8')

              ->set_output(json_encode($response, JSON_PRETTY_PRINT))

              ->_display();

              exit;

            }

            
            elseif ($row->akses=="0") {

              $response = array(

                'Success' => true,

                'Info'    => 'Sukses Login Sebagai Admin',

                'Akses'   => 'admin',

                'id_user'    => $row->id_user,

                'Nama'    => $row->nama,

                'Nik'     => $row->nik,

                'password'     => $row->password,

                'Store_id'=> $row_toko->store_id,

                'id_toko' => $row_toko->id_toko

                );



              $this->output

              ->set_status_header(201)

              ->set_content_type('application/json', 'utf-8')

              ->set_output(json_encode($response, JSON_PRETTY_PRINT))

              ->_display();

              exit;

            }
            elseif( $this->db->get_where('sada_tokoinuser_temp',['id_user' => $user_id, 'id_toko' => $toko_id])->first_row() == null ){
             $response = array(
               'Success' => false,
               'Info' => 'Anda tidak memiliki akses pada store id '.$toko['store_id']);
             $this->output
               ->set_status_header(201)
               ->set_content_type('application/json', 'utf-8')
               ->set_output(json_encode($response, JSON_PRETTY_PRINT))
               ->_display();
               exit;
             }
            elseif ($row->akses=="1") {

              $response = array(

                'Success' => true,

                'Info'    => 'Sukses Login Sebagai SPG',

                'Akses'   => 'spg',

                'Nama'    => $row->nama,

                'Nik'     => $row->nik,

                'Store_id'=> $row_toko->store_id,

                'id_toko' => $row_toko->id_toko,

                'id_user' => $row->id_user

                );



              $this->output

              ->set_status_header(201)

              ->set_content_type('application/json', 'utf-8')

              ->set_output(json_encode($response, JSON_PRETTY_PRINT))

              ->_display();

              exit;

            }else{

              $response = array(

                'Success' => false,

                'Info' => 'Data Gak Ada');



              $this->output

              ->set_status_header(201)

              ->set_content_type('application/json', 'utf-8')

              ->set_output(json_encode($response, JSON_PRETTY_PRINT))

              ->_display();

              exit;

            }

          }else{ //USER TIDAK ADA

            $response = array(

              'Success' => false,

              'Info' => 'Data User Gak Ada');



            $this->output

            ->set_status_header(201)

            ->set_content_type('application/json', 'utf-8')

            ->set_output(json_encode($response, JSON_PRETTY_PRINT))

            ->_display();

            exit;

          }















      }else { //TOKO SUDAH TIDAK AKTIF

        $response = array(

          'Success' => false,

          'Info' => 'Toko Sudah Tidak Aktif');



        $this->output

        ->set_status_header(201)

        ->set_content_type('application/json', 'utf-8')

        ->set_output(json_encode($response, JSON_PRETTY_PRINT))

        ->_display();

        exit;

      }



    }else{ //TOKO GAK ADA

      $response = array(

        'Success' => false,

        'Info' => 'Toko Tidak Di Temukan');



      $this->output

      ->set_status_header(201)

      ->set_content_type('application/json', 'utf-8')

      ->set_output(json_encode($response, JSON_PRETTY_PRINT))

      ->_display();

      exit;

    }

  }





  function getUser($page='', $size='')

  {

    if ($page and $size) {

      return $this->db->get('sada_user', $size, $page);

    }else{

      return $this->db->get('sada_user');

    }

  }

  public function insertUser($data)

  {

    return $this->db->insert("sada_user",$data);

  }

  function getProduk($page='', $size='')

  {

    if ($page and $size) {

      return $this->db->get('sada_produk', $size, $page);

    }else{

      return $this->db->get('sada_produk');

    }

  }



  function getProdukAndCategory()

  {

    return $this->db->select(['p.id_produk','p.nama_produk','(select k.nama from sada_kategori k where p.id_kategori = k.id) kategoriNama'])

    ->from('sada_produk p')

    ->order_by('p.id_kategori')

    ->get();

  }

  function getCountProduk()

  {

    return $this->db->count_all_results('sada_produk', FALSE);

  }

  function getCountUser()

  {

    return $this->db->count_all_results('sada_user', FALSE);

  }

  function updateUser($data='',$id='')

  {

    $dataUpdate = array('nik' =>   $data['Nik'],

     'nama' => $data['Nama'],

     'password' =>  md5(base64_encode(sha1($data['password'])))

     );

    $this->db->where('id_user',$id);

    $this->db->update('sada_user',$dataUpdate);

  }





  public function validateAssignStore($idUser, $idToko)

  {

   $this->db->get_where('sada_tokoinuser',['id_user' => $iduser, 'id_toko' => $idToko]);

 }



 public function inputAbsenStatus($data)

 {

  $this->db->insert('sada_absensi',$data);

}

public function updateEditUser($dataUpdate,$id)

{

  $this->db->where("id_user",$id);

  return $this->db->update("sada_user",$dataUpdate);

}

public function update_target_user($dataUpdate,$id)
{
  $this->db->where("id_target_user",$id);

  return $this->db->update("sada_target_user",$dataUpdate);
}

public function updateEditTlinKota($dataUpdate,$id)

{

  $this->db->where("id_user",$id);

  return $this->db->update("sada_tl_in_kota",$dataUpdate);

}

public function updateEditTlin_user($dataUpdate,$id)

{

  $this->db->where("id_user",$id);

  return $this->db->update("sada_tokoinuser",$dataUpdate);

}



public function getUserTin($user_id)

{

  $sel_tokoinuser = $this->db->select("id_toko")->where("id_user",$user_id)->get("sada_tokoinuser")->row();

  $exp = explode(',', $sel_tokoinuser->id_toko);

  foreach ($exp as $key => $value) {

    $query = "SELECT sada_toko.id_kota,sada_kota.nama_kota FROM sada_toko LEFT JOIN sada_kota ON sada_toko.id_kota=sada_kota.id_kota WHERE id_toko='".$value."'";

    $toko = $this->db->query($query)->row();



    $s = $this->db->select('sada_kota.id_kota,sada_kota.nama_kota,sada_cabang.id_cabang,sada_cabang.nama')->join('sada_kota', 'sada_kota.id_cabang = sada_cabang.id_cabang', 'inner')->where('sada_kota.id_kota',$toko->id_kota)->get('sada_cabang');





  }

}

























/*JUAL PRODUK*/











/*END JUAL PRODUK*/













/*VALIDASI USER PROSES*/



function _CekValidationProses($x='')

{

  if (trim(rawurldecode($this->input->get('secret')))) {

   if ($this->is_Base64(trim(rawurldecode($this->input->get('secret'))))==TRUE) {

     $text = base64_decode(trim(rawurldecode($this->input->get('secret'))));

     $data =  explode("|", $text);

     $cek['id_user'] =  $data['0'];

     $cek['nik']     =  $data['1'];

     $cek['password']=  $data['2'];

     if ($this->input->get('id_user')) {

      if ($this->input->get('id_user')==$cek['id_user']) {

        if ($this->db->get_where('sada_user', $cek)->num_rows()==1) {

         return FALSE;

       }else{

        return TRUE;

      }

    }else{

      return true;

    }

  }else{

    return false;

  }





}else{

  return true;

}

}else{

  return TRUE;

}



}



function is_Base64($s='')

{



          // Check if there are valid base64 characters

  if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;



          // Decode the string in strict mode and check the results

  $decoded = base64_decode($s, true);

  if(false === $decoded) return false;



          // Encode the string again

  if(base64_encode($decoded) != $s) return false;



  return true;



}



/*VALIDASI USER PROSES*/







/* Query Contact Form*/





public function getUserStatus($id)

{

  $query = $this->db->select('akses')

  ->from('sada_user')

  ->where('id_user',$id)

  ->get();

  return $query->first_row();

}

public function insertDataContact($data)

{

  $this->db->insert('sada_form_contact',$data);

  if($this->db->affected_rows() != 1){

    return false;

  }

  return true;

}



/*End Query Contact Form*/



/* Query Promo */



public function insertPromo($data)

{

  $this->db->insert('sada_promo',$data);

  if($this->db->affected_rows() != 1){

    return false;

  }

  return true;



}

/* End Query Promo */



public function inputOutOfStock($data)

{

  $this->db->insert('sada_out_of_stock',$data);

  return ($this->db->affected_rows() == 1);

}





/*QUERY WEB*/



public function insertAssignStore($data)

{

  $this->db->insert_batch('sada_tokoinuser',$data);

}



public function addNewSku($data)

{

  $this->db->insert('sada_produk',$data);

}



public function editSku($paramId)

{

  return  $this->db->select("id_store,id_kategori,nama_produk,price")->from("sada_produk")->where("id_produk",$paramId)->get()->row();

}



public function editUser($paramId)

{

  return  $this->db->select("nik,nama,akses,stay")->from("sada_user")->where("id_user",$paramId)->get()->row();

}


public function updateSku($paramId,$field)

{

  $this->db->where('id_produk', $paramId);

  return $this->db->update('sada_produk',$field);

}





public function insertNewToko($data)

{

  $this->db->insert('sada_toko',$data);

  return ($this->db->affected_rows() == 1);

}

public function getDataToko($id)

{

  $query = $this->db->select('*')->from('sada_toko')->where('id_toko',$id)->get();

  return $query->first_row();

}



public function updateDataToko($data,$id_toko)

{

  $this->db->where('id_toko',$id_toko)

  ->update('sada_toko',$data);

  return ($this->db->affected_rows() == 1);

}

/*END QUERY WEB*/



/* Report Related Query */





public function getTlCabangAndkota($tl_id)

{



  //

  // return $this->db->select('a.*,p.*')

  //        ->from('category a')

  //        ->join('category_produk b', 'b.category_id = a.id','inner')

  //        ->join('produk as p ','b.produk_id = p.id')

  //        ->where('p.id',1)

  //        ->get();



  return $this->db->select(['k.id_kota','k.nama_kota','c.id_cabang','c.nama'])

  ->from('sada_tl_in_kota tk')

  ->join('sada_kota k','tk.id_kota = k.id_kota','inner')

  ->join('sada_cabang c','k.id_cabang = c.id_cabang','inner')

  ->where('id_user',$tl_id)

  ->get();

}





public function getBaName()

{

  return  $this->db->select(['nama','id_user'])

  ->from('sada_user')

  ->where('akses',2)

  ->get();

}

public function get_desc_oos()

{

  return  $this->db->select(['desc','id_desc'])

  ->from('sada_desc_oos')

  ->get()->result();

}

public function getEmail($id_toko)
{
    $data = $this->db->distinct()

    ->select(['cabang.email_pic','cabang.nama','cabang.pic','cabang.aspm','cabang.email_aspm'])

    ->join('sada_kota kota','kota.id_kota = sada_toko.id_kota','inner')
    ->join('sada_cabang cabang','kota.id_cabang = cabang.id_cabang','inner')
    ->get_where('sada_toko',array('id_toko'=>$id_toko));

    return $data;
}

public function getBranchFromName($id_user)

{

  $checkAssignedStore = $this->db->select('id_toko')->from('sada_tokoinuser')->where('id_user',$id_user)->get();

  $first = $checkAssignedStore->first_row();

  $explode = explode(",",$first->id_toko);

  $response = [];

  foreach ($explode as  $value) {

    $query = $this->db->select(['c.id_cabang','c.nama'])

    ->from('sada_cabang c')

    ->join('sada_kota k ', 'k.id_cabang = c.id_cabang','inner')

    ->join('sada_toko t','t.id_kota = k.id_kota','inner')

    ->where('t.id_toko',$value)

    ->get();

    foreach ($query->result() as $result) {

      $response[]=[

      'id' => $result->id_cabang,

      'nama' => $result->nama

      ];

    }

  }

  return $response;

}



public function getTlName()

{

  return  $this->db->select(['nama','id_user'])

  ->from('sada_user')

  ->where('akses',1)

  ->get();



}



public function getToko()

{

  return $this->db->select(['id_toko','nama'])

  ->from('sada_toko')

                  // ->where('status','Y')

  ->get();

}



public function getTokoName($id_toko)

{

  return $this->db->select(['id_toko','nama'])

  ->from('sada_toko')

  ->where('id_toko',$id_toko)

                  // ->where('status','Y')

  ->get();

}





public function getBaAssignedStore($id_user)

{

    // return $this->db->select(['t.nama','t.id_toko'])

    //               ->from('sada_tokoinuser as  a')

    //               ->join('sada_toko as t','a.id_toko = t.id_toko','inner')

    //               ->where('a.id_user',$id_user)

    //               ->get();



  return $this->db->select('id_toko')

  ->from('sada_tokoinuser')

  ->where('id_user',$id_user)

  ->get();



}



public function getAllCabang()

{

  return $this->db->select(['id_cabang','nama'])

  ->from('sada_cabang')

  ->where('status','Y')

  ->get();

}



public function getKotaInCabang($id_cabang)

{

  return $this->db->select(['id_kota','nama_kota'])

  ->from('sada_kota')

  ->where('id_cabang',$id_cabang)

  ->where('status','Y')

  ->get();

}



public function getCabangInKota($id_toko)

{

  return $this->db->select(['c.id_cabang','c.nama'])

  ->from('sada_cabang c')

  ->join('sada_kota k','k.id_cabang = c.id_cabang','inner')

  ->join('sada_toko t','t.id_kota = k.id_kota','inner')

  ->where('t.id_toko',$id_toko)

  ->get();

}





// SKU Report

public function skuReportHeader($filter)

{

  $a = $filter['startTime'];

  $b = $filter['endTime'];

  $c ='asd';

  $this->db->select(['c.nama','k.nama_kota','k.id_kota','k.id_cabang','u.status stay_mobile','u.nama namaBa','u.id_user','pt.id_toko','t.store_id','t.nama namaToko','CAST(pt.tgl  AS DATE) tgl', 'c.nama namaCabang','pt.id_produk'])

  ->from('sada_produk_terjual pt')

  ->join('sada_user u','pt.id_user = u.id_user','inner')

  ->join('sada_toko t', 't.id_toko = pt.id_toko','inner')

  ->join('sada_kota k ', 't.id_kota = k.id_kota','inner')

  ->join('sada_cabang c','k.id_cabang = c.id_cabang','inner')

  ->order_by('u.id_user')

  ->group_by(['pt.id_toko','u.id_user','CAST(pt.tgl  AS DATE)'])

  ->where("tgl BETWEEN '$a'  AND '$b' ");

  if($filter['baName'] != ''){

    $this->db->where('u.id_user',$filter['baName']);

  }

  if($filter['tokoFilter'] != '') {

    $this->db->where('pt.id_toko',$filter['tokoFilter']);

  }



  if($filter['cabangFilter'] != '' && $filter['kotaFilter'] == ''){

    $this->db->where('k.id_cabang',$filter['cabangFilter']);

  }



  if($filter['cabangFilter'] != '' && $filter['kotaFilter'] != ''){

   $this->db->where('k.id_kota',$filter['kotaFilter']);

 }

 return $this->db->get();

}



public function getTlNameInKota($id_kota,$id_user)

{

  return $this->db->select(['u.nama','u.id_user'])

  ->from('sada_user u')

  ->join('sada_tl_in_kota tk','tk.id_user = u.id_user','inner')

  ->where('tk.id_kota', $id_kota)

  ->where('tk.id_user',$id_user)

  ->get();

}



public function acvNatReport()

{

  $qry = "select sada_region.region , sada_cabang.nama as 'nama_cabang' FROM sada_form_contact

  LEFT JOIN sada_toko ON sada_toko.id_toko=sada_form_contact.store_id 

  LEFT JOIN sada_kota ON sada_kota.id_kota=sada_toko.id_kota

  LEFT JOIN sada_cabang ON sada_cabang.id_cabang=sada_kota.id_cabang

  LEFT JOIN sada_region ON sada_region.id_region=sada_cabang.id_region

  ";

  return $this->db->query($qry);

}

public function CountAchievement($id_cabang)

{

  $qry = "SELECT

  (

  SELECT

  SUM(sada_produk_terjual.qty)

  FROM

  sada_produk_terjual AS pr_trj



  LEFT JOIN sada_produk as sd_pr ON pr_trj.id_produk = sd_pr.id_produk

  LEFT JOIN sada_kategori as sd_kat ON sd_pr.id_kategori = sd_kat.id

  WHERE

  pr_trj.id_produk_terjual = sada_produk_terjual.id_produk_terjual



  AND 

  sd_kat.nama='BC'

  ) as qty_BC,

  (

  SELECT

  SUM(sada_produk_terjual.qty)

  FROM

  sada_produk_terjual AS pr_trj



  LEFT JOIN sada_produk as sd_pr ON pr_trj.id_produk = sd_pr.id_produk

  LEFT JOIN sada_kategori as sd_kat ON sd_pr.id_kategori = sd_kat.id

  WHERE

  pr_trj.id_produk_terjual = sada_produk_terjual.id_produk_terjual



  AND 

  sd_kat.nama='BTI'

  ) as qty_BTI,

  (

  SELECT

  SUM(sada_produk_terjual.qty)

  FROM

  sada_produk_terjual AS pr_trj



  LEFT JOIN sada_produk as sd_pr ON pr_trj.id_produk = sd_pr.id_produk

  LEFT JOIN sada_kategori as sd_kat ON sd_pr.id_kategori = sd_kat.id

  WHERE

  pr_trj.id_produk_terjual = sada_produk_terjual.id_produk_terjual



  AND 

  sd_kat.nama='Rusk'

  ) as qty_Rusk,

  (

  SELECT

  SUM(sada_produk_terjual.qty)

  FROM

  sada_produk_terjual AS pr_trj



  LEFT JOIN sada_produk as sd_pr ON pr_trj.id_produk = sd_pr.id_produk

  LEFT JOIN sada_kategori as sd_kat ON sd_pr.id_kategori = sd_kat.id

  WHERE

  pr_trj.id_produk_terjual = sada_produk_terjual.id_produk_terjual



  AND 

  sd_kat.nama='Pudding'

  ) as qty_Pudding,

  (

  SELECT

  SUM(sada_produk_terjual.qty)

  FROM

  sada_produk_terjual AS pr_trj



  LEFT JOIN sada_produk as sd_pr ON pr_trj.id_produk = sd_pr.id_produk

  LEFT JOIN sada_kategori as sd_kat ON sd_pr.id_kategori = sd_kat.id

  WHERE

  pr_trj.id_produk_terjual = sada_produk_terjual.id_produk_terjual



  AND 

  sd_kat.nama='Others'

  ) as qty_Others,

  sada_produk_terjual.qty

  FROM

  sada_produk_terjual

  LEFT JOIN sada_produk ON sada_produk_terjual.id_produk = sada_produk.id_produk

  LEFT JOIN sada_kategori ON sada_produk.id_kategori = sada_kategori.id

  LEFT JOIN sada_toko ON sada_toko.id_toko = sada_produk_terjual.id_toko

  LEFT JOIN sada_kota ON sada_toko.id_kota = sada_kota.id_kota

  LEFT JOIN sada_cabang ON sada_cabang.id_cabang = sada_kota.id_cabang

  LEFT JOIN sada_region ON sada_region.id_region = sada_region.id_region

  WHERE

  sada_cabang.id_cabang = '3'

  ";

  // echo $qry;

  return $this->db->query($qry);

}

public function skuDetails($tanggal,$tipe,$produkId)

{

  return $this->db->select_sum('pt.qty')

  ->from('sada_produk_terjual pt')

  ->join('sada_produk p','pt.id_produk = p.id_produk','inner')

  ->where('DATE(pt.tgl)',$tanggal)

  ->where('pt.tipe',$tipe)

  ->where('p.id_produk',$produkId)

  ->get();

}

public function editTarget($id)

{

  return $this->db->select("sada_target.id_target , sada_target.id_kategori, sada_toko.nama as tok, sada_target.target, sada_target.id_kategori")->from("sada_target")->join('sada_toko','sada_target.id_toko=sada_toko.id_toko','inner')->where("sada_target.id_target",$id)->get();



}

public function editPrice($id)

{

  return $this->db->select("*")->from("sada_produk")->where("sada_produk.id_produk",$id)->get();



}

public function addTokoTarget($id)

{

  return $this->db->select("id_kategori")->from("sada_target")->where("id_toko",$id)->get();



}

public function skuCount($filter,$kategori,$tipe){

 $this->db->select_sum('pt.qty')

 ->select(['pt.tipe','k.nama namaKategori','pt.tgl'])

 ->from('sada_produk_terjual pt')

 ->join('sada_produk p ', 'p.id_produk = pt.id_produk')

 ->join('sada_kategori k','k.id = p.id_kategori')

 ->where('k.nama',$kategori)

 ->where('pt.tipe',$tipe)

 ->where('pt.id_toko',$filter['toko_id'])

 ->where('DATE(pt.tgl)',$filter['tanggal']);

 if($filter['user_id'] != ''){

  $this->db->where('pt.id_user',$filter['user_id']);

}

return $this->db->get();

}



public function testingExcel()

{

  return $this->db->get('sada_kota');

}



// End Sku Report



// Absensi Report

public function getAbsensiUser($tanggal)

{

  $innerQuery = $this->db->select(['user_id','store_id','(select u.nama from sada_user u where a.user_id = u.id_user) nama'])

  ->from('sada_absensi a')

  ->group_by('user_id')

  ->get();

  $count = 0;

  foreach($innerQuery->result() as $user){

    $us[] = $user->user_id;

    foreach($tanggal as $date){

      $singleDate = date('j', strtotime($date));

      $fullQuery =  $this->db->select(['a.tipe','u.nama namaBa','DATE(a.tanggal) tanggal','t.nama namaToko','t.store_id storeId','k.nama_kota namaKota','c.nama namaCabang','t.id_toko idToko','a.user_id idUser'])

      ->from('sada_absensi a')

      ->join('sada_toko t','t.id_toko = a.store_id','inner')

      ->join('sada_kota k ', 't.id_kota = k.id_kota','inner')

      ->join('sada_cabang c','k.id_cabang = c.id_cabang','inner')

      ->join('sada_user u','a.user_id = u.id_user','inner')

      ->where('DATE(a.tanggal)',$date)

      ->where("u.id_user = (select distinct user_id from sada_absensi where user_id ='$user->user_id')" )

                        // ->where("t.id_toko = (select distinct store_id from sada_absensi where store_id ='$user->store_id')")

      ->get();



      foreach($fullQuery->result() as $data){

        if($fullQuery->num_rows() != 0){

          $result[$user->user_id]=[

          $singleDate => $data->tipe,

          'namaBa' => $data->namaBa,

          'tanggal' => $data->tanggal,

          'namaToko' => $data->namaToko,

          'storeId' => $data->storeId,

          'namaToko' => $data->namaToko,

          'namaKota' => $data->namaKota,

          'namaCabang' => $data->namaCabang,

          'idToko' => $data->idToko,

          'idUser' => $data->idUser

          ];

        }

        else{

          $result[$user->user_id]= [

          $singleDate => 'none'

          ];

        }

      }

    }

    // $a = count($result);

    // for($i = 0 ; $i < $a ; $i++){

    //   $response[]=[

    //     $user->nama => $result[$i]

    //   ];

    // }

    // $response[]=[

    //   $user->nama => $result

    // ];

  }

  // return $result;

  // foreach($innerQuery->result() as $value){

  //   $fullQuery =  $this->db->select(['a.tipe','u.nama namaBa','DATE(a.tanggal) tanggal','t.nama namaToko','t.store_id storeId','k.nama_kota namaKota','c.nama namaCabang','t.id_toko idToko','a.user_id idUser'])

  //                   ->from('sada_absensi a')

  //                   ->join('sada_toko t','t.id_toko = a.store_id','inner')

  //                   ->join('sada_kota k ', 't.id_kota = k.id_kota','inner')

  //                   ->join('sada_cabang c','k.id_cabang = c.id_cabang','inner')

  //                   ->join('sada_user u','a.user_id = u.id_user','inner')

  //                   ->where('DATE(a.tanggal)',$tanggal)

  //                   ->where("u.id_user = (select distinct user_id from sada_absensi where user_id ='$value->user_id')" )

  //                   ->where("t.id_toko = (select distinct store_id from sada_absensi where store_id ='$value->store_id')")

  //                   ->get();

  //     if($fullQuery->num_rows() != 0){

  //       $result[]=[

  //             $date => $fullQuery->row()->tipe,

  //             'namaBa' => $fullQuery->row()->namaBa,

  //             'tanggal' => $fullQuery->row()->tanggal,

  //             'namaToko' => $fullQuery->row()->namaToko,

  //             'storeId' => $fullQuery->row()->storeId,

  //             'namaToko' => $fullQuery->row()->namaToko,

  //             'namaKota' => $fullQuery->row()->namaKota,

  //             'namaCabang' => $fullQuery->row()->namaCabang,

  //             'idToko' => $fullQuery->row()->idToko,

  //             'idUser' => $fullQuery->row()->idUser

  //       ];

  //     }else{

  //       $result[] = [

  //         $date => 'none'

  //       ];

  //     }

  // }

  // return $result;



  // $date = date('j', strtotime($tanggal));

  // $result = [];

  // $query =  $this->db->select(['a.tipe','u.nama namaBa','DATE(a.tanggal) tanggal','t.nama namaToko','t.store_id storeId','k.nama_kota namaKota','c.nama namaCabang','t.id_toko idToko','a.user_id idUser'])

  //                 ->from('sada_absensi a')

  //                 ->join('sada_toko t','t.id_toko = a.store_id','inner')

  //                 ->join('sada_kota k ', 't.id_kota = k.id_kota','inner')

  //                 ->join('sada_cabang c','k.id_cabang = c.id_cabang','inner')

  //                 ->join('sada_user u','a.user_id = u.id_user','inner')

  //                 ->where('DATE(a.tanggal)',$tanggal)

  //                 // ->where('u.id_user',$idBa)

  //                 ->where('t.id_toko',$idToko)

  //                 ->get();

  // if($query->num_rows() == 0 ){

  //   $result[] = [

  //     $date => 'none',

  //   ];

  return $result;

  // }

  // foreach ($query->result() as $value) {

  //   $result[] = [

  //     $date => $value->tipe,

  //     'namaBa' => $value->namaBa,

  //     'tanggal' => $value->tanggal,

  //     'namaToko' => $value->namaToko,

  //     'storeId' => $value->storeId,

  //     'namaToko' => $value->namaToko,

  //     'namaKota' => $value->namaKota,

  //     'namaCabang' => $value->namaCabang,

  //     'idToko' => $value->idToko,

  //     'idUser' => $value->idUser

  //   ];

  // }

  // return $result;

}

// End Report Absen



// Out Of Stock Report Query



public function outOfStockReport($filter)

{

  $a = $filter['startDate'];

  $b = $filter['endDate'];

  $this->db->select(['o.id id_oos','o.keterangan','GROUP_CONCAT(p.nama_produk) namaProduk','CAST(o.date  AS DATE) date','t.nama namaToko','c.nama namaCabang','k.nama_kota','t.store_id','u.nama namaBa','o.tipe'])

  ->from('sada_out_of_stock o')

  ->join('sada_produk p ','o.produk_id = p.id_produk','inner')

  ->join('sada_toko t','o.store_id = t.id_toko', 'inner')

  ->join('sada_kota k ', 't.id_kota = k.id_kota','inner')

  ->join('sada_cabang c','k.id_cabang = c.id_cabang','inner')

  ->join('sada_user u','o.user_id = u.id_user','inner')

  ->group_by(['o.user_id','CAST(o.date  AS DATE)','o.store_id'])

  ->where("o.date BETWEEN '$a'  AND '$b' ");

  if($filter['filterName'] != ''){

    $this->db->where('o.user_id',$filter['filterName']);

  }

  if($filter['filterToko'] != ''){

    $this->db->where('t.id_toko',$filter['filterToko']);

  }

  if($filter['filterCabang'] != '' && $filter['filterKota'] == ''){

    $this->db->where('k.id_cabang',$filter['filterCabang']);

  }



  if($filter['filterCabang'] != '' && $filter['filterKota'] != ''){

   $this->db->where('k.id_kota',$filter['filterKota']);

 }

 return $this->db->get();

}

// End Out of Stock Report Query



// Achievement Report

public function achievementSamplingReport()

{

  return $this->db->select(['(select count(store_id) from sada_form_contact where kategori_id = 1) storeBc',

    '(select sum(samplingQty) from sada_form_contact where kategori_id = 1)  samplingBc',

    "(select sum(samplingQty) from sada_form_contact where kategori_id = 1 and beli= 'Y')  strikeSamplingBc",

    '(select count(store_id) from sada_form_contact where kategori_id = 2) storeBTI',

    '(select sum(samplingQty) from sada_form_contact where kategori_id = 2)  samplingBTI',

    "(select sum(samplingQty) from sada_form_contact where kategori_id = 2 and beli= 'Y')  strikeSamplingBTI",

    '(select count(store_id) from sada_form_contact where kategori_id = 3) storeRusk',

    '(select sum(samplingQty) from sada_form_contact where kategori_id = 3)  samplingRusk',

    "(select sum(samplingQty) from sada_form_contact where kategori_id = 3 and beli= 'Y')  strikeSamplingRusk",

    '(select count(store_id) from sada_form_contact where kategori_id = 4) storePudding',

    '(select sum(samplingQty) from sada_form_contact where kategori_id = 4)  samplingPudding',

    "(select sum(samplingQty) from sada_form_contact where kategori_id = 4 and beli= 'Y')  strikeSamplingPudding",

    '(select count(store_id) from sada_form_contact where kategori_id = 5) storeOthers',

    '(select sum(samplingQty) from sada_form_contact where kategori_id = 5)  samplingOthers',

    "(select sum(samplingQty) from sada_form_contact where kategori_id = 5 and beli= 'Y')  strikeSamplingOthers",

    '(select count(id_toko) from sada_toko ) totalToko'

    ])

  ->from('sada_form_contact')

  ->get()

  ->first_row();

}



public function nationalAchievementReport()

{

  $target = '100';

  

}

// End Achievement Report



/*  End of Report Related Query*/







public function checkInputPhoneNumber($phoneNumber,$month)

{

  return $this->db->select('id')

  ->from('sada_form_contact')

  ->where('month(tgl_contact)',$month)

  ->where('telp',$phoneNumber)

  ->get()

  ->first_row();

}



  //<CABANG>



public function getCabang()

{

  return $this->db->get('sada_cabang');

}









public function addNewCabang($data)

{

  $this->db->insert('sada_cabang',$data);

}



public function editCabang($paramId)

{

  return $this->db->select("id_cabang,nama,target,pic")->from("sada_cabang")->where("id_cabang",$paramId)->get()->row();

}



public function updateCabang($paramId,$field)

{

  $this->db->where('id_cabang', $paramId);

  return $this->db->update('sada_cabang',$field);

}



  //<CABANG/>





  //<KOTA>



public function getKota()

{

  return $this->db->get('sada_kota');

}



public function addNewKota($data)

{

  $this->db->insert('sada_kota',$data);

}



public function editKota($paramId)

{

  return $this->db->select("id_kota,id_cabang,nama_kota")->from("sada_kota")->where("id_kota",$paramId)->get()->row();

}

public function editToko($paramId)

{

  return $this->db->select("id_toko,store_id,id_kota,nama")->from("sada_toko")->where("id_toko",$paramId)->get()->row();

}

public function updateKota($paramId,$field)

{

  $this->db->where('id_kota', $paramId);

  return $this->db->update('sada_kota',$field);

}



  //<KOTA/>

public function updateToko($paramId,$field)

{

  $this->db->where("id_toko",$paramId);

  return $this->db->update("sada_toko",$field);

}

public function updateTarget($paramId,$field)

{

  $this->db->where("id_target",$paramId);

  return $this->db->update("sada_target",$field);

}

public function deletUser($paramId,$field)

{

  $this->db->where("id_user",$paramId);

  return $this->db->update("sada_user",$field);

}



/*CSS & JS*/



function CssdataTable()

{



  $data = array(

    'assets/global/plugins/datatables/datatables.min.css',

    'assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css'

    );



  return $data;

}



function JsdataTable()

{

  $data = array(

    'assets/global/scripts/datatable.js',

    // 'assets/table.js',

    'assets/global/plugins/datatables/datatables.min.js',

    'assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js',

    'assets/pages/scripts/table-datatables-managed.min.js'

    );







  return $data;

}









/*END CSS SAMA JS*/





/*FUNGSI HARAPAN JAYA SUMBER MAKMUR JANGAN ERROR*/



function _getNameCabang($id_kota='')

{

 if ($id_kota=='') {

   return '<center><span class="label label-sm label-danger"> Error </span></center>';

 }else{

  $q = $this->db->select('c.nama')->from('sada_kota k')->join('sada_cabang c','c.id_cabang = k.id_cabang')->where('k.id_kota',$id_kota )->get();

  if ($q->num_rows() == 1) {

    return $q->row()->nama;

  } else {

    return '<center><span class="label label-sm label-danger"> Error Cabang </span></center>';

  }



}



}



/*END FUNGSI HARAPAN JAYA SUMBER MAKMUR JANGAN ERROR*/

}

