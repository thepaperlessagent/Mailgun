<?php

namespace Bogardo\Mailgun\Mail;

class MessageBuilder extends \Mailgun\Message\MessageBuilder
{
    /**
     * @param string      $attachmentData
     * @param string|null $attachmentName
     *
     * @return bool
     */
    public function addAttachmentData($attachmentData, $attachmentName = null)
    {
        if (isset($this->files['attachment'])) {
            $attachment = [
                'fileContent' => $attachmentData,
                'filename' => $attachmentName,
            ];
            array_push($this->files['attachment'], $attachment);
        } else {
            $this->files['attachment'] = [
                [
                    'fileContent' => $attachmentData,
                    'filename' => $attachmentName,
                ],
            ];
        }

        return true;
    }

    public function getFiles() {
        return isset( $this->message['attachment'] ) ? $this->message['attachment'] : [];
    }
}
