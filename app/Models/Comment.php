<?php
class Comment extends Model {
  static $belongs_to = [['post']];
}