<?php


class ClientAccess_SaveData {

	public function save( $type, $postid, $postdata ) {
		
		switch ($type) {
			case 'bid':
				$result = $this->saveBid( $type, $postid, $postdata );
				break;
			case 'client':
				clientaccess_save_client($postid, $_POST);
				break;
			case 'project':
				clientaccess_save_project($postid, $_POST);
				break;
			case 'timeentry':
				clientaccess_save_timeentry($postid, $_POST);
				break;
			case 'contract':
				clientaccess_save_contract($postid, $_POST);
				break;
		}

		return $result;

	}

	/**
	 * Save Bids Metadata
	 * 
	 * @param string $type 
	 * @param int $postid 
	 * @param array $postdata 
	 * @return mixed
	 */

	private function saveBid( $type, $postid, $postdata ) {

		/* $items = $postdata['item'];
		$amount = $postdata['amount'];
		$unitary = $postdata['unitary'];
		$total= $postdata['total'];

		for ( $i=0; $i<count($items); $i++) {
			$finalitems[] = array( $items[$i], $amount[$i], $unitary[$i], $total[$i] );
		}
		*/
		// $result['values'] 			= update_post_meta( $postid, 'values', $finalitems);
		$result['client'] 			= update_post_meta ($postid, 'client', esc_attr ( $postdata ['client'] ) );
		$result['clientincharge'] 	= update_post_meta ($postid, 'clientincharge', esc_attr ( $postdata ['clientincharge'] ) );
		$result['employeeincharge'] = update_post_meta ($postid, 'employeeincharge', esc_attr ( $postdata ['employeeincharge'] ) );
		$result['contactinfo'] 		= update_post_meta ($postid, 'contactinfo', esc_attr ( $postdata ['contactinfo'] ) );
		$result['prologue'] 		= update_post_meta ($postid, 'prologue', esc_attr ( $postdata ['prologue'] ) );
		$result['epilogue'] 		= update_post_meta ($postid, 'epilogue', esc_attr ( $postdata ['epilogue'] ) );
		$result['deadline'] 		= update_post_meta ($postid, 'deadline', esc_attr ( $postdata ['deadline'] ) );

		$result['valuetable'] 		= update_post_meta ($postid, 'valuetable', esc_attr ( $postdata ['valuetable'] ) );

		return $result;

	}

}



?>