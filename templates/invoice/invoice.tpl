<section class="invoice">
<div class="text-center bg-info" style="background-color:#d9edf7">
	<div class="text-uppercase" style="text-align:center;text-transform:uppercase"><strong>{INVOICE}</strong></div>
</div>
	<table border="0" cellpadding="0" cellaspacing="0" style="width:100%">
		<tr valign="top">
		<td style="width:50%"><img src="{LOGO}"></td>
		<td style="width:50%;text-align:right">
			<address style="padding-top:15px;text-align:right">
                <strong>{COMPANY_NAME}</strong><br>
                {COMPANY_ADDRESS1}<br>
                {COMPANY_ADDRESS2}<br><br>
               	{COMPANY_VAT}<br/>
             </address>
         </td>
      	</tr>
	</table>
	
	<hr />

	<table border="0" cellpadding="0" cellaspacing="0" style="width:100%">
		<tr valign="top">
		<td style="width:33%">
			<address>
                <strong>{CLIENT_NAME}</strong><br>
                {CLIENT_ADDRESS}<br>
               	{CLIENT_VAT}<br/>
             </address>
		</td>
		<td style="width:33%;text-align:center">
			<img src="{PAID}" style="width:120px">
		</td>
		<td style="width:33%;text-align:right">
			{lang.Invoice Number}: {INVOICE_NUMBER}<br>
			{lang.Invoice Date}: {INVOICE_DATE}<br>
			{lang.Order Number}: {ORDER_NUMBER}<br>
			{lang.Invoice Amount}: {INVOICE_AMOUNT}			
		</td>
	</tr>
	</table>
	
	<hr />



          <div class="row">
            <div class="col-xs-12 table-responsive">
              {ITEMS}
            </div>
          </div>
          
          <p>{TEXT}</p>
          <hr />

			{if payment}
              <p><b>{lang.Payment}:</b><br>
              {PAYMENT}
              </p>
              <img src="{SITE_ROOT}/images/credit_cards.gif" style="margin-bottom:5px">
				
			{/if}
        </section>