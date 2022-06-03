<!DOCTYPE html>
<html>

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Saudi Attractions Tickets Confirmation</title>
</head>

<body>
<div style="margin: 0;padding: 0;">
        <table width="600" border="0" cellspacing="0" style="table-layout:fixed;border-collapse:collapse;width:600px;margin:auto;font-family: Helvetica,Arial,sans-serif;">
                <tbody>
                <!-- cover image -->
                <tr>
                        <td style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" bgcolor="#f0f0f0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                <td width="600" height="200" style="border-collapse:collapse;padding: 0;">
                                                        <div>
                                                                <img src="{!! asset('/uploads/cover.png') !!}" alt="cover" width="600" height="200" style="display: block;">
                                                        </div>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td bgcolor="#f0f0f0" width="600" height="25" style="border-collapse:collapse;padding: 0;"></td>
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <!-- event image -->
                <tr>
                        <td style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" bgcolor="#f0f0f0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                                <td width="520" height="170" bgcolor="#307dc1" style="border-collapse:collapse;width:520px;padding: 0;">
                                                        <div>
                                                                <img src="{!! asset($event_image) !!}" alt="cover" width="520" height="170" style="display: block;">
                                                        </div>
                                                </td>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <!-- event title  -->
                <tr>
                        <td style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" bgcolor="#f0f0f0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                                <td bgcolor="#ffffff" width="520" style="border-collapse:collapse;width:520px;padding: 0;">
                                                        <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                <tbody>
                                                                <tr>
                                                                        <td height="20" style="border-collapse:collapse;padding: 0;"></td>
                                                                </tr>
                                                                <tr>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                        <td width="360" style="border-collapse:collapse;padding: 0;">
                                                                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #444444;font-size: 18px;font-weight:bold;">{!! $event_name !!}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td height="10" style="border-collapse:collapse;padding: 0;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #4dc181;font-size: 16px;">{!! $event_date !!} at {!! $event_time !!}</td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                </table>
                                                                        </td>
                                                                        <td width="100" style="border-collapse:collapse;padding: 0;color: #2e9e96;font-size: 30px;">{!! $total !!}<span style="padding: 0;color: #2e9e96;font-size:16px;">SAR</span></td>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                </tr>
                                                                <tr>
                                                                        <td height="20" style="border-collapse:collapse;padding: 0;"></td>
                                                                </tr>
                                                                </tbody>
                                                        </table>
                                                </td>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <!-- separator -->
                <tr>
                        <td style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" bgcolor="#f0f0f0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                                <td bgcolor="#ffffff" width="520" style="border-collapse:collapse;width:520px;padding: 0;">
                                                        <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                <tbody>
                                                                <tr>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                        <td bgcolor="#e1e1e1" width="460" height="1" style="border-collapse:collapse;padding: 0;">
                                                                        </td>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                </tr>
                                                                </tbody>
                                                        </table>
                                                </td>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <!-- event location  -->
                <tr>
                        <td style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" bgcolor="#f0f0f0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                                <td bgcolor="#ffffff" width="520" style="border-collapse:collapse;width:520px;padding: 0;">
                                                        <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                <tbody>
                                                                <tr>
                                                                        <td height="20" style="border-collapse:collapse;padding: 0;"></td>
                                                                </tr>
                                                                <tr>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                        <td width="460" style="border-collapse:collapse;padding: 0;">
                                                                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #999999;font-size: 14px;font-weight:bold;">EVENT LOCATION</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td height="5" style="border-collapse:collapse;padding: 0;"></td>
                                                                                        </tr>
                                                                                        <tr>

<td style="border-collapse:collapse;padding: 0;color: #444444;font-size: 14px;"><a href="{!! $event_location !!}">Location</a></td>                                                                                        </tr>
                                                                                        </tbody>
                                                                                </table>
                                                                        </td>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                </tr>
                                                                <tr>
                                                                        <td height="20" style="border-collapse:collapse;padding: 0;"></td>
                                                                </tr>
                                                                </tbody>
                                                        </table>
                                                </td>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <!-- name-->
                <tr>
                        <td style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" bgcolor="#f0f0f0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                                <td bgcolor="#ffffff" width="520" style="border-collapse:collapse;width:520px;padding: 0;">
                                                        <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                <tbody>
                                                                <tr>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                        <td width="200" style="border-collapse:collapse;padding: 0;">
                                                                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #999999;font-size: 14px;font-weight:bold;">NAME</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td height="5" style="border-collapse:collapse;padding: 0;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #444444;font-size: 14px;">{!! $name !!}</td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                </table>
                                                                        </td>
                                                                        <td width="200" style="border-collapse:collapse;padding: 0;">
                                                                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #999999;font-size: 14px;font-weight:bold;">MOBILE NUMBER</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td height="5" style="border-collapse:collapse;padding: 0;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #444444;font-size: 14px;">{!! $mobile_number !!}</td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                </table>
                                                                        </td>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                </tr>
                                                                <tr>
                                                                        <td height="20" style="border-collapse:collapse;padding: 0;"></td>
                                                                </tr>
                                                                </tbody>
                                                        </table>
                                                </td>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <!-- payment-->
                <tr>
                        <td style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" bgcolor="#f0f0f0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                                <td bgcolor="#ffffff" width="520" style="border-collapse:collapse;width:520px;padding: 0;">
                                                        <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                <tbody>
                                                                <tr>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                        <td width="200" style="border-collapse:collapse;padding: 0;">
                                                                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #999999;font-size: 14px;font-weight:bold;">PAYMENT METHOD</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td height="5" style="border-collapse:collapse;padding: 0;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #444444;font-size: 14px;">{!! $payment_method !!}</td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                </table>
                                                                        </td>
                                                                        <td width="200" style="border-collapse:collapse;padding: 0;">
                                                                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #999999;font-size: 14px;font-weight:bold;">ORDER NUMBER</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td height="5" style="border-collapse:collapse;padding: 0;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #444444;font-size: 14px;">{!! $order_number !!}</td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                </table>
                                                                        </td>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                </tr>
                                                                <tr>
                                                                        <td height="20" style="border-collapse:collapse;padding: 0;"></td>
                                                                </tr>
                                                                </tbody>
                                                        </table>
                                                </td>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <!-- ticket-->
                <tr>
                        <td style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" bgcolor="#f0f0f0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                                <td bgcolor="#ffffff" width="520" style="border-collapse:collapse;width:520px;padding: 0;">
                                                        <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                <tbody>
                                                                <tr>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                        <td width="200" style="border-collapse:collapse;padding: 0;">
                                                                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #999999;font-size: 14px;font-weight:bold;">NUMBER OF TICKETS</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td height="5" style="border-collapse:collapse;padding: 0;"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td style="border-collapse:collapse;padding: 0;color: #444444;font-size: 14px;">{!! $number_of_tickets !!}</td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                </table>
                                                                        </td>
                                                                        <td width="200" style="border-collapse:collapse;padding: 0;">
                                                                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                                                                        <tbody>
                                                                                        @foreach($tickets_id as $ticket_id)
                                                                                                <tr>
                                                                                                        <td style="border-collapse:collapse;padding: 0;color: #999999;font-size: 14px;font-weight:bold;">TICKET NUMBER</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                        <td height="5" style="border-collapse:collapse;padding: 0;"></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                        <td style="border-collapse:collapse;padding: 0;color: #444444;font-size: 14px;"> {!! $ticket_id->id !!}</td>
                                                                                                </tr>
                                                                                        @endforeach
                                                                                        </tbody>
                                                                                </table>
                                                                        </td>
                                                                        <td width="30" style="border-collapse:collapse;width:30px;padding: 0;"></td>
                                                                </tr>
                                                                <tr>
                                                                        <td height="20" style="border-collapse:collapse;padding: 0;"></td>
                                                                </tr>
                                                                </tbody>
                                                        </table>
                                                </td>
                                                <td width="40" style="border-collapse:collapse;width:40px;padding: 0;"></td>
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <tr>
                        <td bgcolor="#f0f0f0" height="30" style="border-collapse:collapse;padding: 0;"></td>
                </tr>
                <!-- website -->
                <tr>
                        <td bgcolor="#f0f0f0" width="600" align="center" style="border-collapse:collapse;color: #6d6d6d;font-size: 14px;font-weight: bold;padding: 0;">
<a href="http://www.saudiattractions.net" title="" target="_blank" style="color: #4dc181;font-size: 11px;font-weight:bold;">WWW.SAUDIATTRACTIONS.COM</a></td>
                </tr>
                <tr>
                        <td bgcolor="#f0f0f0" height="15" style="border-collapse:collapse;padding: 0;"></td>
                </tr>
                <tr>
                        <td bgcolor="#f0f0f0" width="600" align="center" style="border-collapse:collapse;color: #363636;font-size: 11px;padding: 0;">FOLLOW US</td>
                </tr>
                <tr>
                        <td bgcolor="#f0f0f0" height="15" style="border-collapse:collapse;padding: 0;"></td>
                </tr>
                <!-- social links -->
                <tr>
                        <td bgcolor="#f0f0f0" style="border-collapse:collapse;padding: 0;">
                                <table border="0" cellspacing="0" style="border-collapse:collapse;">
                                        <tbody>
                                        <tr>
                                                @foreach($event_social as $social)
                                                        <td width="225" style="border-collapse:collapse;padding: 0;"></td>
                                                        <td width="50" align="center" style="border-collapse:collapse;padding: 0;">
                                                                <a href="{{$social['pivot']['url']}}" title="" target="_blank">
                                                                        <img src="{{asset($social['media']['image'])}}" alt="" width="47" height="47" style="display: block;">
                                                                </a>
                                                        </td>
                                                @endforeach
                                        </tr>
                                        </tbody>
                                </table>
                        </td>
                </tr>
                <tr>
                        <td bgcolor="#f0f0f0" height="20" style="border-collapse:collapse;padding: 0;"></td>
                </tr>
                </tbody>
        </table>
</div>
</body>

</html>
