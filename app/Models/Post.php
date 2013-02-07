<?php
class Post extends Model {
  static $has_many = [['comments']];
}