<?php
    class Common
    {
        function executeCurl($url, $isRaw = false, $tm = false, $postData = false, $fromWhere = false, $authData = false)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            if(!empty($postData))
            {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }

            if(!empty($tm))
            {
                curl_setopt($ch, CURLOPT_TIMEOUT, $tm);
            }

            if(!empty($authData))
            {
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, $authData['user'] . ":" . $authData['pwd']);
            }

            $result = curl_exec($ch);
            curl_close($ch);

            if(empty($isRaw))
            {
                $result = json_decode($result, 1);
            }

            return $result;
        }
		
		public function clearParam($d)
		{
			if(count($d))
			{
				foreach($d as $k => $v)
				{
					$_GET[$k] = str_replace('-',' ',$v);
				}
			}
		}
    }
?>