<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_model
{    
    public function find($order_id)
    {
        $sql = "SELECT * FROM orders WHERE orders.id = ? ";

        $query = $this->db->query($sql, [$order_id]);
        $order = $query->row();

        if($order){
            $order->order_details = $this->Order_detail_model->getByOrderId($order->id);
        }

        return $order;
    }

    public function generateInvoiceNumber()
    {
        $query = $this->db->query("SELECT COUNT(*) as x FROM orders WHERE DATE(created_at) = ?", date('Y-m-d'));
        $row = $query->row();
        $x = $row->x;

        return 'FD/'. date('YmdHis'. ($x + 1));
    }

    public function generateStatusMessage($order = null)
    {
        if(is_array($order)){
            $orders = $order;
            $orders = array_map(function($order){
                $order->status_message = '';
                switch ($order->status) { // 'pending','cancelled','payment-verification','payment-denied','paid','shipping','delivered'
                    case 'pending':
                        $order->status_message = 'Pesanan anda belum di verifikasi, harap mengupload bukti pembayaran';
                        break;
                    case 'cancelled':
                        $order->status_message = 'Pesanan anda telah dibatalkan';
                        break;
                    case 'payment-verification':
                        $order->status_message = 'Pesanan dalam proses verifikasi pembayaran oleh admin kami';
                        break;
                    case 'payment-denied':
                        $order->status_message = 'Proses verifikasi pembayaran anda tidak valid, harap verifikasi ulang';
                        break;
                    case 'paid':
                        $order->status_message = 'Proses verifikasi pembayaran anda diterima, pesanan akan segera di proses';
                        break;
                    case 'shipping':
                        $order->status_message = 'Pesanan anda sedang dalam proses pengiriman';
                        break;
                    case 'delivered':
                        $order->status_message = 'Pesanan anda telah berhasil dikirim';
                        break;
                }
                return $order;
            }, $orders);

            return $orders;
        }

        return false;
    }
}
