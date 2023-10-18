<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE]><html xmlns="http://www.w3.org/1999/xhtml" class="ie"><![endif]--><!--[if !IE]><!-->
<html style="margin: 0;padding: 0;" xmlns="http://www.w3.org/1999/xhtml"><!--<![endif]-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /><!--<![endif]-->
  <meta name="viewport" content="width=device-width" />
  <meta name="x-apple-disable-message-reformatting" />

  <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ asset('assets/css/emailreset.css') }}">


  <meta name="robots" content="noindex,nofollow" />
  <meta property="og:title" content="My First Campaign" />

  <style>
    .actionbtns a {
      border-radius: 4px;display: 
      inline-block;
      font-size: 14px;
      font-weight: bold;
      line-height: 24px;
      padding: 12px 24px;
      text-align: center;
      text-decoration: none !important;
      transition: opacity 0.1s ease-in;
      color: #ffffff !important;
      background-color: #e0475d;
      font-family: Ubuntu, sans-serif;
    }
  </style>
</head>


<body class="main full-padding" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;">
  <table class="wrapper" style="border-collapse: collapse;table-layout: fixed;min-width: 320px;width: 100%;"
    cellpadding="0" cellspacing="0" role="presentation">
    <tbody>
      <tr>
        <td>
          <!-- barner -->
          <div role="banner">
            <div class="preheader"
              style="Margin: 0 auto;max-width: 560px;min-width: 280px; width: 280px;width: calc(28000% - 167440px);">
              <div style="border-collapse: collapse;display: table;width: 100%;">
                <!--[if (mso)|(IE)]><table align="center" class="preheader" cellpadding="0" cellspacing="0" role="presentation"><tr><td style="width: 280px" valign="top"><![endif]-->
                <div class="snippet"
                  style="display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 140px; width: 140px;width: calc(14000% - 78120px);padding: 10px 0 5px 0;color: #787778;font-family: Ubuntu,sans-serif;">

                </div>
                <!--[if (mso)|(IE)]></td><td style="width: 280px" valign="top"><![endif]-->
                <div class="webversion"
                  style="display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 139px; width: 139px;width: calc(14100% - 78680px);padding: 10px 0 5px 0;text-align: right;color: #787778;font-family: Ubuntu,sans-serif;">

                </div>
                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
              </div>
            </div>
            <div class="header"
              style="Margin: 0 auto;max-width: 600px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);"
              id="emb-email-header-container">
              <!--[if (mso)|(IE)]><table align="center" class="header" cellpadding="0" cellspacing="0" role="presentation"><tr><td style="width: 600px"><![endif]-->
              <div class="logo emb-logo-margin-box"
                style="font-size: 26px;line-height: 32px;Margin-top: 6px;Margin-bottom: 20px;color: #c3ced9;font-family: Roboto,Tahoma,sans-serif;Margin-left: 20px;Margin-right: 20px;"
                align="center">
                <div class="logo-center" align="center" id="emb-email-header">
                  <!-- <img src="https://api.influenzit.com/assets/images/logo.png"> -->
                  <!-- <img style="max-width: 180px"  width="180" src="https://api.influenzit.com/assets/images/influenzit_logo.png"> -->
                </div>
              </div>
              <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
            </div>
          </div>

          <!-- body -->
          <div>
            <div class="layout one-col fixed-width stack"
              style="Margin: 0 auto;max-width: 600px;min-width: 320px; overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;">
              <img style="width:100%"  src="{{ asset('assets/images/email_header_small.png') }}">
              <div class="layout__inner"
                style="border-collapse: collapse;display: table;width: 100%;background-color: #ffffff;">
                <!--[if (mso)|(IE)]><table align="center" cellpadding="0" cellspacing="0" role="presentation"><tr class="layout-fixed-width" style="background-color: #ffffff;"><td style="width: 600px" class="w560"><![endif]-->
                <div class="column"
                  style="text-align: left;color: #787778;font-size: 16px;line-height: 24px;font-family: Ubuntu,sans-serif; padding: 5%;">

                  <div style="Margin-left: 20px;Margin-right: 20px;Margin-top: 24px;">
                    <div style="mso-line-height-rule: exactly;line-height: 20px;font-size: 1px;">&nbsp;</div>
                  </div>

                  <div style="Margin-left: 0px;Margin-right: 0px;">
                    <div style="mso-line-height-rule: exactly;mso-text-raise: 11px;vertical-align: middle;">
                      <h5
                        style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #565656;font-size: 20px;line-height: 38px;text-align: left;">
                        <b>Dear {{ $notifiable->name }}</b>
                      </h5>
                     
                      <div style="Margin-top: 5px;Margin-bottom: 5px; background: whitesmoke; padding: 0px 5px; border-radius: 5px;">&nbsp;<br />
                      </div>

                    </div>
                  </div>

                  <div style="Margin-left: 20px;Margin-right: 20px;">
                    <div class="btn btn--flat btn--large actionbtns" style="Margin-bottom: 20px;text-align: justify;">
                        {!! html_entity_decode( $content['body'] )!!}
                    </div>
                  </div>



                  <div style="Margin-left: 20px;Margin-right: 20px;">
                    <div class="btn btn--flat btn--large actionbtns" style="Margin-bottom: 20px;text-align: center;">
                      {!! $content ['actions'] !!}
                    </div>
                  </div>


                  <div style="Margin-left: 20px;Margin-right: 20px;">
                    <div class="btn btn--flat btn--large actionbtns" style="Margin-bottom: 20px;text-align: justify;">
                        {!! html_entity_decode( $content['line1'] ?? '' )!!}
                        {!! html_entity_decode( $content['line2'] ?? '' )!!}
                        {!! html_entity_decode( $content['line3'] ?? '' )!!}
                       
                        <p>Best Regards,</p>
                        <p>The Influenzit Team</p>
                    </div>
                  </div>


                  <div style="Margin-left: 20px;Margin-right: 20px; margin-top:80px">
                    <!-- <p style="Margin-top: 20px;Margin-bottom: 20px; text-align: center;">Thanks for using our application</p> -->
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- footer -->
          <div role="contentinfo">
            <div style="line-height:4px;font-size:4px;" id="footer-top-spacing">&nbsp;</div>
            <div class="layout email-flexible-footer email-footer"
              style="Margin: 0 auto;max-width: 600px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;"
              dir="rtl" id="footer-content">
              <div class="layout__inner right-aligned-footer"
                style="border-collapse: collapse;display: table;width: 100%;">
                <!--[if (mso)|(IE)]><table align="center" cellpadding="0" cellspacing="0" role="presentation"><tr class="layout-email-footer"><![endif]-->
                <!--[if (mso)|(IE)]><td><table cellpadding="0" cellspacing="0"><![endif]-->
                <!--[if (mso)|(IE)]><td valign="top"><![endif]-->
                <div class="column"
                  style="text-align: right;font-size: 12px;line-height: 19px;color: #787778;font-family: Ubuntu,sans-serif;"
                  dir="ltr">
                  <div class="footer-logo emb-logo-margin-box"
                    style="font-size: 26px;line-height: 32px;Margin-top: 6px;Margin-bottom: 20px;color: #7b663d;font-family: Roboto,Tahoma,sans-serif;"
                    align="center">
                    <div style="margin-left: 20px;" emb-flexible-footer-logo align="center"><img
                        style="border: 0;display: block;height: auto; max-width: 80px;"
                        src="https://api.influenzit.com/assets/images/influenzit_logo.png" alt="" height="80" /></div>
                  </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]><td valign="top" class="w60"><![endif]-->
                  <div class="column"
                    style="text-align:center; font-size: 12px;line-height: 19px;color: #787778;font-family: Ubuntu,sans-serif;"
                    dir="ltr">
                    <div
                      style="margin-left: 0;margin-right: 0;Margin-top: 10px;Margin-bottom: 10px; display: flex; justify-content: space-around;">
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]><td valign="top" class="w260"><![endif]-->
                  <table
                    style="border-collapse: collapse;table-layout: fixed;display: inline-block;width: 156px; display: flex; width: 100%; justify-content: center;"
                    cellpadding="0" cellspacing="0">
                    <tbody>
                      <tr>
                        <td>
                          <div class="column js-footer-additional-info"
                            style="text-align: right;font-size: 12px;line-height: 19px;color: #787778;font-family: Ubuntu,sans-serif;width: 100%;"
                            dir="ltr">
                            <div style="margin-left: 0;margin-right: 0;Margin-top: 10px;Margin-bottom: 10px;">



                              <div class="email-footer__additional-info"
                                style="font-size: 12px;line-height: 19px;margin-bottom: 15px;">
                                <span>
                                  <preferences style="text-decoration: underline;" lang="en">Preferences</preferences>
                                  &nbsp;&nbsp;|&nbsp;&nbsp;
                                </span>
                                <unsubscribe style="text-decoration: underline;">Unsubscribe</unsubscribe>
                              </div>
                              <!--[if mso]>&nbsp;<![endif]-->
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!--[if (mso)|(IE)]></table></td><![endif]-->
                  <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                </div>
              </div>
              <div style="line-height:40px;font-size:40px;" id="footer-bottom-spacing">&nbsp;</div>
            </div>
          </div>

        </td>
      </tr>
    </tbody>
  </table>

</body>

</html>