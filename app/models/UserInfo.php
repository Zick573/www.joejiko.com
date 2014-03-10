<?php
class UserInfo extends Eloquent {
  protected $primaryKey = 'user_id';
  protected $table = 'user_info';
  protected $fillable = [
      'user_id',
      'provider_name',
      'provider_uid',
      'profile_url',
      'website_url',
      'photo_url',
      'display_name',
      'description',
      'first_name',
      'last_name',
      'gender',
      'language',
      'age',
      'birth_day',
      'birth_month',
      'birth_year',
      'email',
      'email_verified',
      'phone',
      'address',
      'country',
      'region',
      'city',
      'zip'
  ];
}