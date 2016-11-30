<?php

namespace Biz\DownloadActivity\Dao\Impl;


use Biz\DownloadActivity\Dao\DownloadFileRecordDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class DownloadFileRecordDaoImpl extends GeneralDaoImpl implements DownloadFileRecordDao
{
    protected $table = 'download_file_record';

    public function declares()
    {
        return array(
            'timestamps' => array('createdTime')
        );
    }


}