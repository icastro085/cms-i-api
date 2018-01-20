<?php
namespace API\Model;

class Content extends \Illuminate\Database\Eloquent\Model {
  protected $table = "content";
  protected $primaryKey = "id";
  protected $keyType = "string";
  public $incrementing = false;
  public $timestamps = false;
}