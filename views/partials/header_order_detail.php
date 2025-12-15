<?php include_once __DIR__ . '/../../config/helpers.php'; ?>
<!DOCTYPE html>
<html lang="vi"><head><meta charset="utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>SAPHIRA - Xác Nhận Đơn Hàng</title><base href="<?php echo base_url(); ?>"><style>
:root{ --font-display:'Manrope',sans-serif; --font-serif:'Cormorant Garamond',serif; --color-primary:#f4c025; --color-gold:#D4AF37; --color-bg-start:#1a1a1a; --color-bg-end:#000000; --color-header:rgba(0,0,0,0.8); --color-text-light:#ffffff; --color-text-dim:rgba(255,255,255,0.8); --color-border:rgba(255,255,255,0.1); --color-bg-content:rgba(0,0,0,0.5); --color-black:#000; --border-radius:.5rem; --border-radius-2xl:2rem; }
*{box-sizing:border-box;margin:0;padding:0} body{font-family:var(--font-display);background-image:linear-gradient(to bottom,var(--color-bg-start),var(--color-bg-end));color:var(--color-text-light);min-height:100vh;display:flex;flex-direction:column;overflow-x:hidden}
.checkout-header-sticky{position:sticky;top:0;z-index:50;display:flex;align-items:center;justify-content:center;white-space:nowrap;border-bottom:1px solid var(--color-gold);padding:1rem;background-color:var(--color-header);backdrop-filter:blur(4px)}
.header-logo-container{display:flex;align-items:center;gap:1rem}
.order-main-container{flex:1;display:flex;justify-content:center;width:100%;padding:2.5rem 1rem} @media(min-width:768px){.order-main-container{padding:4rem 2rem}}
.order-content-wrapper{width:100%;max-width:1024px}
.order-page-header{margin-bottom:2.5rem;text-align:center}
.order-icon-wrapper{display:flex;justify-content:center;margin-bottom:1.5rem}
.order-icon-wrapper span{display:flex;height:5rem;width:5rem;align-items:center;justify-content:center;border-radius:9999px;background-color:rgba(212,175,55,0.1);border:1px solid var(--color-gold);color:var(--color-gold);font-size:3rem}
.order-page-title{font-family:var(--font-serif);font-size:2.25rem;font-weight:700;color:var(--color-gold);letter-spacing:.025em}
.order-page-subtitle{margin-top:.75rem;color:var(--color-text-dim);font-size:1rem;max-width:42rem;margin-left:auto;margin-right:auto}
.order-layout-grid{display:grid;grid-template-columns:1fr;gap:2rem} @media(min-width:1024px){.order-layout-grid{grid-template-columns:repeat(5,1fr);gap:3rem}}
.order-details-box,.shipping-info-box{background-color:var(--color-bg-content);padding:1.5rem;border-radius:var(--border-radius-2xl);box-shadow:0 4px 10px rgba(0,0,0,.3);border:1px solid var(--color-border);backdrop-filter:blur(12px)} @media(min-width:768px){.order-details-box,.shipping-info-box{padding:2rem}}
.box-title{font-family:var(--font-serif);font-size:1.5rem;font-weight:700;color:var(--color-gold);margin-bottom:1.5rem}
.order-details-box{grid-column:span 1} @media(min-width:1024px){.order-details-box{grid-column:span 3}}
.order-info-list{display:flex;flex-direction:column;gap:1rem;margin-bottom:1.5rem;font-size:.875rem}
.order-info-row{display:flex;justify-content:space-between;align-items:center}
.order-info-row span:first-child{color:var(--color-text-dim)} .order-info-row .order-id{font-weight:700;color:var(--color-gold)}
.divider{border-top:1px solid var(--color-border);margin:1.5rem 0}
.order-items-list{display:flex;flex-direction:column;gap:1.25rem}
.order-item{display:flex;align-items:center;gap:1rem}
.order-item-image{width:4rem;height:4rem;border-radius:1rem;object-fit:cover;flex-shrink:0}
.order-item-info{flex:1;min-width:0} .order-item-name{font-weight:600;color:var(--color-text-light);white-space:nowrap;overflow:hidden;text-overflow:ellipsis} .order-item-meta{font-size:.75rem;color:var(--color-text-dim)}
.order-item-price{text-align:right} .order-item-price p{font-weight:500;color:var(--color-text-light);font-size:.875rem;white-space:nowrap} .order-item-price span{font-size:.75rem;color:var(--color-text-dim);white-space:nowrap}
.order-total-row{display:flex;justify-content:space-between;align-items:center} .order-total-row span:first-child{font-size:1.125rem;font-weight:600;color:var(--color-text-light)} .total-price{font-size:1.5rem;font-weight:700;color:var(--color-gold)}
.shipping-info-box{grid-column:span 1} @media(min-width:1024px){.shipping-info-box{grid-column:span 2}} .shipping-info-list{display:flex;flex-direction:column;gap:.75rem;font-size:.875rem} .shipping-info-list span:first-child{color:var(--color-text-dim)}
.order-actions{margin-top:3rem;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1rem} @media(min-width:640px){.order-actions{flex-direction:row}}
.btn-outline-gold{width:100%;height:3rem;display:flex;align-items:center;justify-content:center;border-radius:1rem;padding:0 2rem;border:1px solid var(--color-gold);color:var(--color-gold);font-size:1rem;font-weight:600;letter-spacing:.025em;transition:background-color .15s ease} .btn-outline-gold:hover{background-color:rgba(212,175,55,0.1)} @media(min-width:640px){.btn-outline-gold{width:auto}}
.btn-primary-gold{width:100%;height:3rem;display:flex;align-items:center;justify-content:center;border-radius:1rem;padding:0 2rem;background-color:var(--color-gold);color:#000;font-size:1rem;font-weight:600;letter-spacing:.025em;transition:opacity .15s ease;box-shadow:0 4px 10px rgba(212,175,55,0.2)} .btn-primary-gold:hover{opacity:.9} @media(min-width:640px){.btn-primary-gold{width:auto}}
.checkout-footer{background-color:#000;border-top:1px solid var(--color-border);margin-top:4rem;padding:1.5rem 1rem;text-align:center} .checkout-footer p{color:var(--color-text-dim);font-size:.875rem;max-width:1280px;margin:0 auto}
</style></head><body><header class="checkout-header-sticky"><a class="header-logo-container" href="main.php?r=home" style="display:flex;align-items:center"><div style="height:135px;width:160px;overflow:hidden;display:flex;align-items:center"><img src="<?php echo asset('img/logo/logo.png'); ?>" alt="SAPHIRA" style="height:135px;display:block" /></div></a></header>
