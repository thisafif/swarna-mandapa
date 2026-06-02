<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Booking Confirmed — Swarna Mandapa</title>
<style>
  body { margin:0; padding:0; background:#f5f0e8; font-family: Georgia, 'Times New Roman', serif; color:#3a3028 }
  .wrap { max-width:560px; margin:32px auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08) }
  .header { background:linear-gradient(135deg,#C9A96E,#8B6914); padding:36px 32px; text-align:center; color:#fff }
  .header h1 { margin:0 0 4px; font-size:22px; font-weight:700; letter-spacing:.05em }
  .header p { margin:0; font-size:13px; opacity:.85 }
  .badge-paid { display:inline-block; background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.4); border-radius:50px; padding:4px 16px; font-size:11px; letter-spacing:.1em; margin-top:12px }
  .body { padding:28px 32px }
  .greeting { font-size:16px; margin-bottom:20px }
  .ref-box { background:#faf7f2; border:1px dashed #C9A96E; border-radius:8px; padding:14px 20px; text-align:center; margin-bottom:24px }
  .ref-box .label { font-size:10px; text-transform:uppercase; letter-spacing:.12em; color:#999; margin-bottom:4px }
  .ref-box .code { font-size:22px; font-weight:700; letter-spacing:.1em; color:#C9A96E }
  .section-title { font-size:11px; text-transform:uppercase; letter-spacing:.12em; color:#999; border-bottom:1px solid #eee; padding-bottom:6px; margin-bottom:12px; margin-top:24px }
  .detail-row { display:flex; justify-content:space-between; padding:6px 0; font-size:13px; border-bottom:1px solid #f5f0e8 }
  .detail-row:last-child { border-bottom:none }
  .detail-label { color:#999 }
  .detail-val { font-weight:600; text-align:right }
  .total-row { display:flex; justify-content:space-between; padding:12px 0; font-size:16px; font-weight:700; border-top:2px solid #eee; margin-top:8px }
  .total-amount { color:#C9A96E; font-size:18px }
  .footer { background:#faf7f2; padding:20px 32px; text-align:center; font-size:11px; color:#aaa; border-top:1px solid #eee }
  .footer a { color:#C9A96E; text-decoration:none }
  .check-icon { width:56px; height:56px; background:rgba(255,255,255,.2); border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:12px; font-size:26px }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <div class="check-icon">✓</div>
    <h1>Booking Confirmed!</h1>
    <p>Your stay at Villa Swarna Mandapa is officially secured.</p>
    <div class="badge-paid">✦ PAID ✦</div>
  </div>

  <div class="body">
    @php
      $guestName     = trim($booking->first_name . ' ' . $booking->last_name);
      $ci            = $booking->check_in;
      $co            = $booking->check_out;
      $nights        = (new DateTime($ci))->diff(new DateTime($co))->days;
      $pricePerNight = (int) $booking->price_per_night;
      $base          = $pricePerNight * $nights;
      $discount      = (float) $booking->discount_amount;
      $total         = (int) $booking->total_price;
      $promoCode     = $booking->promo_code;
      $paidAt        = $booking->paid_at
                        ? \Carbon\Carbon::parse($booking->paid_at)->format('d M Y, H:i')
                        : date('d M Y, H:i');
    @endphp

    <p class="greeting">Dear <strong>{{ $guestName }}</strong>,</p>
    <p style="font-size:13px;color:#666;margin-bottom:20px">
      Thank you for your booking! We're excited to welcome you to Swarna Mandapa.
      Below are your booking details for reference.
    </p>

    <div class="ref-box">
      <div class="label">Booking Reference</div>
      <div class="code">{{ $booking->booking_code }}</div>
    </div>

    <div class="section-title">Stay Details</div>
    <div class="detail-row">
      <span class="detail-label">Property</span>
      <span class="detail-val">Villa Superior — Swarna Mandapa</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Check-in</span>
      <span class="detail-val">{{ date('D, d M Y', strtotime($ci)) }} · from 15:00</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Check-out</span>
      <span class="detail-val">{{ date('D, d M Y', strtotime($co)) }} · before 11:00</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Duration</span>
      <span class="detail-val">{{ $nights }} Nights</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Guests</span>
      <span class="detail-val">{{ $booking->guests }} Guest{{ $booking->guests > 1 ? 's' : '' }}</span>
    </div>

    <div class="section-title">Payment Summary</div>
    <div class="detail-row">
      <span class="detail-label">Villa ({{ $nights }} nights)</span>
      <span class="detail-val">Rp {{ number_format($base, 0, ',', '.') }}</span>
    </div>
    @if($discount > 0)
    <div class="detail-row">
      <span class="detail-label">Promo{{ $promoCode ? ' ('.$promoCode.')' : '' }}</span>
      <span class="detail-val" style="color:#22c55e">— Rp {{ number_format($discount, 0, ',', '.') }}</span>
    </div>
    @endif
    <div class="total-row">
      <span>Total Paid</span>
      <span class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>
    <p style="font-size:11px;color:#aaa;margin-top:4px">Paid on {{ $paidAt }}</p>

    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px 16px;margin-top:20px;font-size:13px">
      <strong style="color:#15803d">Need help?</strong><br>
      <span style="color:#666">Contact us at <a href="mailto:info@swarnamandapa.com" style="color:#C9A96E">info@swarnamandapa.com</a> or WhatsApp kami.</span>
    </div>
  </div>

  <div class="footer">
    © {{ date('Y') }} Swarna Mandapa · Jl. Nuansa Angkasa III No 7, Ubud, Bali<br>
    <a href="#">swarnamandapa.com</a>
  </div>

</div>
</body>
</html>