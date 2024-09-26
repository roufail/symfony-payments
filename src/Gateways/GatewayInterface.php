<?php


namespace App\Gateways;

/**
 * author osama saed
 * GatewayInterface
 */

interface GatewayInterface
{
	public function fetch();
	public function create(array $data);
	public function getName();
}