<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Nomination Submitted</title>
  </head>
  <body
    style="
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
      font-family: Arial, sans-serif;
      line-height: 24px;
    "
  >
    <table
      style="
        max-width: 600px;
        width: 100%;
        margin: 0 auto;
        background-color: #ffffff;
        font-size: 14px;
      "
      cellpadding="0"
      cellspacing="0"
    >
      <tbody>
        <tr>
          <td align="center">
            <img
              src="{{ asset('assets/icons/infosys_logo.svg') }}"
              width="120"
              height="40"
              alt="Infosys"
              style="padding: 12px"
            />
          </td>
        </tr>
        <tr>
          <td align="center" style="padding-bottom: 12px">
            <strong style="font-size: 16px">New Nomination Submitted</strong>
          </td>
        </tr>
        <tr>
          <td style="padding: 0 16px 12px">Dear Admin,</td>
        </tr>
        <tr>
          <td style="padding: 0 16px 12px">
            {{ $nominator_name }} has submitted a nomination for <strong>{{ $event_name }} ({{ $event_code }}).</strong>
          </td>
        </tr>

        <!-- Nominee Details Card -->
        <tr>
          <td style="padding: 0 16px 12px">
            <table style="width: 100%; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; padding: 12px;" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="padding-bottom: 8px; font-weight: bold; font-size: 13px; color: #555; border-bottom: 1px solid #eee;" colspan="2">Nominee Details</td>
                </tr>
                <tr>
                  <td style="padding: 8px 0 4px; font-weight: bold; width: 35%;">Name:</td>
                  <td style="padding: 8px 0 4px;">{{ $first_name }} {{ $last_name }}</td>
                </tr>
                <tr>
                  <td style="padding: 4px 0; font-weight: bold;">Email:</td>
                  <td style="padding: 4px 0;">{{ $email }}</td>
                </tr>
                <tr>
                  <td style="padding: 4px 0; font-weight: bold;">Company:</td>
                  <td style="padding: 4px 0;">{{ $company }}</td>
                </tr>
                <tr>
                  <td style="padding: 4px 0; font-weight: bold;">Title:</td>
                  <td style="padding: 4px 0;">{{ $title }}</td>
                </tr>
                <tr>
                  <td style="padding: 4px 0 8px; font-weight: bold;">Unit:</td>
                  <td style="padding: 4px 0 8px;">{{ $unit }} ({{ $sub_unit }})</td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>

        <!-- CTA -->
        <tr>
          <td style="padding: 12px 16px" align="center">
            <a
              href="{{ url('/admin/dashboard') }}"
              style="
                background-color: #007cc3;
                color: #fff;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 4px;
                display: inline-block;
                font-weight: bold;
              "
              target="_blank"
              >View Nomination</a
            >
          </td>
        </tr>

        <!-- Regards -->
        <tr>
          <td style="padding: 12px 16px 0px">Best regards,</td>
        </tr>
        <tr>
          <td style="padding: 0 16px 16px">Events CoE</td>
        </tr>

        <!-- Footer -->
        <tr>
          <td
            style="
              border-top: 1px solid #ddd;
              font-size: 12px;
              padding: 12px 16px 0px;
              color: #777;
            "
          >
            This is an automated notification. Please do not reply to this
            email.
          </td>
        </tr>
        <tr>
          <td style="padding: 0 16px 12px; font-size: 12px; color: #777">
            © 2026 Infosys. All rights reserved.
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
