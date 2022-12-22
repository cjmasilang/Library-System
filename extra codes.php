				<tr>
			        <th>Assisting Personel</th>
			        <td>
			          <select class="form-control" name="personel">
			            <option selected></option>
			              <?php
			              include_once "classes/borrow.php";
			              $borrow = new Borrower($db);
			              $stmt = $borrow->viewAllBorrower();

			              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			                extract($row);
			                echo "<option value={$borrowersId}>{$firstname} {$lastname}</option>";
			              }
			              ?>
			          </select>
			        </td>
			      </tr>

			      
			      <select class="form-control" name="borrowDate">
			            <option selected></option>
			              <?php
			              $stmt = $trans->checkTrans();

			              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			                extract($row);
			                echo "<option value={$dateborrowed}>{$dateborrowed}</option>";
			              }
			              ?>
			          </select>