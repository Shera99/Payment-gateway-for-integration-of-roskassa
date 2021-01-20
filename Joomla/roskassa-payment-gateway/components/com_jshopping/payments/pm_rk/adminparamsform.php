<div class="col100">

	<fieldset class="adminform">

		<table class="admintable" width="100%">

			<tr>

				<td class="key">

					<?php echo _JSHOP_RK_MERCHANT_URL;?>

				</td>

				<td>

					<input type="text" class="inputbox" name="pm_params[merchant_url]" size="45" value="<?php echo $params['merchant_url']?>" />

					<?php echo JHTML::tooltip(_JSHOP_RK_MERCHANT_URL_DESCR);?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_RK_MERCHANT_ID;?>

				</td>

				<td>

					<input type="text" class="inputbox" name="pm_params[merchant_id]" size="45" value="<?php echo $params['merchant_id']?>" />

					<?php echo JHTML::tooltip(_JSHOP_RK_MERCHANT_ID_DESCR);?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_RK_SECRET_KEY1;?>

				</td>

				<td>

					<input type="text" class="inputbox" name="pm_params[secret_key1]" size="45" value="<?php echo $params['secret_key1']?>" />

					<?php echo JHTML::tooltip(_JSHOP_RK_SECRET_KEY_DESCR);?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_RK_LOG_FILE;?>

				</td>

				<td>

					<input type="text" class="inputbox" name="pm_params[log_file]" size="45" value="<?php echo $params['log_file']?>" />

					<?php echo JHTML::tooltip(_JSHOP_RK_LOG_FILE_DESCR);?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_TRANSACTION_PENDING;?>

				</td>

				<td>

					<?php 

						echo JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_pending_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_pending_status']);

						echo " ".JHTML::tooltip(_JSHOP_RK_TRANSACTION_PENDING_DESCR);

					?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_TRANSACTION_END;?>

				</td>

				<td>

					<?php              

						print JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_end_status'] );

						echo " ".JHTML::tooltip(_JSHOP_RK_TRANSACTION_SUCCESS_DESCR);

					?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_TRANSACTION_FAILED;?>

				</td>

				<td>

					<?php 

						echo JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_failed_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_failed_status']);

						echo " ".JHTML::tooltip(_JSHOP_RK_TRANSACTION_FAILED_DESCR);

					?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_RK_SUCCESS_URL;?>

				</td>

				<td>

					<input type="text" class="inputbox" name="pm_params[success_url]" size="45" value="<?php echo $success_url ?>" />

					<?php echo JHTML::tooltip(_JSHOP_RK_SUCCESS_URL_DESCR);?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_RK_FAIL_URL;?>

				</td>

				<td>

					<input type="text" class="inputbox" name="pm_params[fail_url]" size="45" value="<?php echo $fail_url ?>" />

					<?php echo JHTML::tooltip(_JSHOP_RK_FAIL_URL_DESCR);?>

				</td>

			</tr>

			<tr>

				<td class="key">

					<?php echo _JSHOP_RK_STATUS_URL;?>

				</td>

				<td>

					<input type="text" class="inputbox" name="pm_params[status_url]" size="45" value="<?php echo $status_url ?>" />

					<?php echo JHTML::tooltip(_JSHOP_RK_STATUS_URL_DESCR);?>

				</td>

			</tr>

			<tr>

				<td>

					<a target="_blank" href="https://roskassa.net/">roskassa.net</a>

				</td>

			</tr>

		</table>

	</fieldset>

</div>

<div class="clr"></div>

