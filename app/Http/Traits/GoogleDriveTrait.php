<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Google_Service_Drive;

/**
 * Class GoogleDriveTrait
 *
 * @package \App\Http\Traits
 */
trait GoogleDriveTrait {

	protected $pageSize = 100;
	protected $fields   = 'files(id, name, mimeType, size, webContentLink)';

	/**
	 * Returns a Google Drive Service.
	 *
	 * @param \Google_Client
	 * @return Google_Service_Drive
	 */
	function getDrive($client)
	{
		return new Google_Service_Drive($client);
	}

	/**
	 * Set Drive Service optParams.
	 * @param $pageToken
	 *
	 * @return array
	 */
	function setOptParams($pageToken)
	{
		$optParams = $pageToken ? ['pageToken' => $pageToken] : [];
		$optParams['fields']   = $this->fields;
		$optParams['pageSize'] = $this->pageSize;

		return $optParams;
	}

	/**
	 * Fetch Drive files.
	 * @param $listedFiles
	 * @param $files
	 *
	 * @return array
	 */
	function fetchFiles($listedFiles, &$files)
	{
		foreach ($listedFiles->getFiles() as $file) {
			$files[] = $this->setFileMetaData($file);
		}

		return $files;
	}

	/**
	 * Set File Meta Data
	 * @param $file
	 *
	 * @return array
	 */
	function setFileMetaData($file)
	{
		$fileMetaData = [
			'file_id'       => $file->id,
			'title'         => $file->name,
			'mime_type'     => $file->mimeType,
			'download_url'  => $file->webContentLink,
			'size'          => $file->size != null ? ($file->size / 1024) : null,
			'user_id'       => auth()->user()->id,
			'created_at'    => Carbon::now(),
			'updated_at'    => Carbon::now()
		];

		return $fileMetaData;
	}
}
