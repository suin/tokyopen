<?php

class Pengin_Math
{
	/**
	 * 10進整数をビット配列に変換する
	 * @param int $dec
	 * @return array
	 *
	 * 例えば、7 を渡したら [1, 2, 4] に分解する
	 */
	public static function decToBitArray($dec)
	{
		$bin  = decbin($dec);
		$bits = str_split($bin);
		$bits = array_reverse($bits);
		$bits = array_filter($bits); // 0ビット除去

		foreach ( $bits as $pos => $one ) {
			$bits[$pos] = pow(2, $pos);
		}

		return array_values($bits); // 添字振りなおし
	}
}
