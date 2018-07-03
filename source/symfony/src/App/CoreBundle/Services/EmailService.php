<?php

namespace App\CoreBundle\Services;

use App\CoreBundle\Entity\EmailTriggers;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EmailService
{

    public $mailer;
    private $entityManager;

    public function __construct(EntityManager $entityManager, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $subject
     * @param string $from
     * @param $to
     * @param string $content
     * @param null $userData
     * @param null $order
     * @param bool $test
     * @return bool|string
     */
    public function sendEmail($subject, $from = "removed", $to, $content = "", $userData = null, $order = null, $test = false)
    {
        $to = $this->normalize($to);

        if ($test) {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom([$from => 'Equipo ABRE - TEST'])
                ->setTo($to)
                ->setBody(
                    $content,
                    'text/html'
                );

            try {
                $mail = $this->mailer->send($message);
                return true;
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
        }


        if ($userData) {
            $html = $this->parseShortCodes($content, $userData, $order);
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom([$from => 'Equipo ABRE'])
                ->setTo($to)
                ->setBcc(['removed' => 'removed', 'removed' => 'removed'])
                ->setBody(
                    $html,
                    'text/html'
                );

            try {
                $mail = $this->mailer->send($message);
                return true;
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
        }
    }

    /**
     * @param $content
     * @param $userData
     * @param $order
     * @return mixed
     */
    public function parseShortCodes($content, $userData, $order)
    {
        // [[USER_NAME]]
        if ($userData) {
            $content = str_replace('[[USER_NAME]]', $userData->getFullName(), $content);
        }

        // [[ORDER_NUMBER]]
        if ($order) {
            $content = str_replace('[[ORDER_NUMBER]]', $order->getWoocomerceOrderId(), $content);
        }

        // [[TRACKING_CODE]]
        if ($order) {
            $content = str_replace('[[TRACKING_CODE]]', $order->getTrackingCode(), $content);
        }

        // [[ORDER_ADDRESS]]
        if ($order) {
            $content = str_replace('[[ORDER_ADDRESS]]', $order->getUserAddressOrderList(), $content);
        }

        // [[PRODUCT_LIST]]
        if ($order) {
            $data = '<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">';

            foreach ($order->getCompraArticulo() as $articulo) {
                $data .= '<tr>
		<td align="left" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="150" style="max-width:100%;">
				<tr>
					<td align="left">
						<img src="https://www.abrecultura.com/api/media/' . $articulo->getArticulo()->getProducto()->getMedia()->first()->getMedia()->getId() . '/150/150" width="150" height="150" style="max-width:100%;" alt="Text" title="Text" />
					</td>
				</tr>
			</table>
		</td>
		<td align="right" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="350" style="max-width:100%;">
				<tr>
					<td align="left">
						<h3 style="color:#333333;line-height:125%;font-family:Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:30px;margin-bottom:3px;text-align:left;">' . $articulo->getArticulo()->getName() . '<br>(x' . $articulo->getCantidad() . ')</h3>
						<div style="text-align:left;font-family:Arial,sans-serif;font-size:15px;margin-bottom:0;color:#808080;line-height:135%;"><span style="font-size:14px;">$</span> ' . $articulo->getArticulo()->getPrice() . '</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>';
            }

            foreach ($order->getCompraCombo() as $combo) {
                $data .= '<tr>
		<td align="left" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="150" style="max-width:100%;">
				<tr>
					<td align="left">
						<img src="https://www.abrecultura.com/api/media/' . $combo->getCombo()->getMedia()->first()->getMedia()->getId() . '/150/150" width="150" height="150" style="max-width:100%;" alt="Text" title="Text" />
					</td>
				</tr>
			</table>
		</td>
		<td align="right" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="350" style="max-width:100%;">
				<tr>
					<td align="left">
						<h3 style="color:#333333;line-height:125%;font-family:Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:30px;margin-bottom:3px;text-align:left;">' . $combo->getCombo()->getName() . '<br>(x' . $combo->getCantidad() . ')</h3>
						<div style="text-align:left;font-family:Arial,sans-serif;font-size:15px;margin-bottom:0;color:#808080;line-height:135%;"><span style="font-size:14px;">$</span> ' . $combo->getCombo()->getPrice() . '</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>';
            }


            $data .= '</table>';

            $content = str_replace('[[PRODUCT_LIST]]', $data, $content);
        }

        return $content;
    }

    /**
     * @param $tienda
     * @param $envio
     * @param $estado
     * @param null $delay
     * @return bool|null|object
     */
    public function checkTriggers($tienda = null, $envio = null, $estado = null, $delay = null)
    {
        $trigger = $this->entityManager->getRepository(EmailTriggers::class)->findOneBy(
            [
                'tienda' => $tienda,
                'envio' => $envio,
                'estado' => $estado,
                'delay' => $delay
            ]
        );


        if (!$trigger) {
            $noStore = $this->entityManager->getRepository(EmailTriggers::class)->findOneBy(
                [
                    'tienda' => NULL,
                    'envio' => $envio,
                    'estado' => $estado,
                    'delay' => $delay
                ]
            );

            if (!$noStore) {
                $default = $this->entityManager->getRepository(EmailTriggers::class)->findOneBy(
                    [
                        'tienda' => NULL,
                        'envio' => NULL,
                        'estado' => $estado,
                        'delay' => $delay
                    ]
                );

                if ($default) {
                    return $default;
                }

                return false;
            }

            return $noStore;
        }

        return $trigger;
    }

    function normalize($cadena)
    {
        $no_permitidas = ["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹"];
        $permitidas = ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E"];
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return strtolower($texto);
    }

}