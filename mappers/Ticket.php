<?php
/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvticket\Mappers;

use Modules\Kvticket\Models\Ticket as TicketModel;

class Ticket extends \Ilch\Mapper
{
    /**
     * Gets the Tickets.
     *
     * @param array $where
     * @return TicketModel[]|array
     */
    public function getTickets($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('kvticket')
            ->where($where)
            ->order(['datetime' => 'DESC'])
            ->execute()
            ->fetchRows();

        $tickets = [];
        if (empty($entryArray)) {
            return $tickets;
        }

        foreach ($entryArray as $entries) {
            $entryModel = new TicketModel();
            $entryModel->setId($entries['id']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setText($entries['text']);
            $entryModel->setDatetime($entries['datetime']);
            $entryModel->setStatus($entries['status']);
            $tickets[] = $entryModel;
        }

        return $tickets;
    }

    /**
     * Get Ticket by given Id.
     *
     * @param integer $id
     * @return TicketModel|null
     */
    public function getTicketById($id)
    {
        $team = $this->getTickets(['id' => $id]);

        return reset($team);
    }

    /**
     * Inserts or updates Ticket Model.
     *
     * @param TicketModel $ticket
     */
    public function save(TicketModel $ticket)
    {
        $fields = [
            'title' => $ticket->getTitle(),
            'text' => $ticket->getText(),
            'status' => $ticket->getStatus()
        ];

        if ($ticket->getId()) {
            $this->db()->update('kvticket')
                ->values($fields)
                ->where(['id' => $ticket->getId()])
                ->execute();
        } else {
            $this->db()->insert('kvticket')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Delete Ticket with given Id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('kvticket')
            ->where(['id' => $id])
            ->execute();

    }
}