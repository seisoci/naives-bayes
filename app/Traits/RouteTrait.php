<?php

namespace App\Traits;

use App\Models\KembalikanBarang;
use App\Models\KurangBarang;
use App\Models\Setting;
use App\Models\TambahBarang;

trait RouteTrait
{

  public function responseredirect($match)
  {
    return match ($match) {
      'habis_pakai' => route('barang-habis-pakai.index'),
      'tidak_habis_pakai' => route('barang-tidak-habis-pakai.index'),
      'aset' => route('barang-tidak-bergerak.index'),
    };
  }

  public function responseredirectupdate($match, $id)
  {
    return match ($match) {
      'habis_pakai' => route('barang-habis-pakai.show', $id),
      'tidak_habis_pakai' => route('barang-tidak-habis-pakai.show', $id),
      'aset' => route('barang-tidak-bergerak.index'),
    };
  }

  public function generatePrefixTambah()
  {
    $query = TambahBarang::selectRaw('
      IFNULL(MAX(SUBSTRING_INDEX(`tambah_barang`.`kode`, "-", "-1")), 0) + 1 AS no_urut
    ')
      ->leftJoin('barang', 'barang.id', '=', 'tambah_barang.barang_id');

    $prefix =  NULL;
    if (auth()->user()->hasRole('super-admin', 'admin-dinas', 'admin-tu')) {
      $query = $query->where('barang.user_type', 'dinas');
      $prefix = Setting::whereName('prefix')->first()['value'];
    } elseif (auth()->user()->hasRole('admin-splp', 'admin-skb')) {
      $query = $query->where('barang.user_id', auth()->id());
      $prefix = auth()->user()->prefix;
    }
    $noUrut = strtoupper($prefix)."-BM-".$query->first()['no_urut'];
    return $noUrut;
  }

  public function generatePrefixKurang()
  {
    $query = KurangBarang::selectRaw('
      IFNULL(MAX(SUBSTRING_INDEX(`kurang_barang`.`kode`, "-", "-1")), 0) + 1 AS no_urut
    ')
      ->leftJoin('barang', 'barang.id', '=', 'kurang_barang.barang_id');

    $prefix =  NULL;
    if (auth()->user()->hasRole('super-admin', 'admin-dinas', 'admin-tu')) {
      $query = $query->where('barang.user_type', 'dinas');
      $prefix = Setting::whereName('prefix')->first()['value'];
    } elseif (auth()->user()->hasRole('admin-splp', 'admin-skb')) {
      $query = $query->where('barang.user_id', auth()->id());
      $prefix = auth()->user()->prefix;
    }
    $noUrut = strtoupper($prefix)."-BK-".$query->first()['no_urut'];
    return $noUrut;
  }

  public function generatePrefixKembali()
  {
    $query = KembalikanBarang::selectRaw('
      IFNULL(MAX(SUBSTRING_INDEX(`kembalikan_barang`.`kode`, "-", "-1")), 0) + 1 AS no_urut
    ')
      ->leftJoin('barang', 'barang.id', '=', 'kembalikan_barang.barang_id');

    $prefix =  NULL;
    if (auth()->user()->hasRole('super-admin', 'admin-dinas', 'admin-tu')) {
      $query = $query->where('barang.user_type', 'dinas');
      $prefix = Setting::whereName('prefix')->first()['value'];
    } elseif (auth()->user()->hasRole('admin-splp', 'admin-skb')) {
      $query = $query->where('barang.user_id', auth()->id());
      $prefix = auth()->user()->prefix;
    }
    $noUrut = strtoupper($prefix)."-BR-".$query->first()['no_urut'];
    return $noUrut;
  }

}
