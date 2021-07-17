<?php
require('mysql_table.php');
require('connection.php');

$Date = $_REQUEST['surch_date'];
$ShowDate = date('F, Y', strtotime($Date));
		
class PDF extends PDF_MySQL_Table
{
function Footer()
				{
					global $comp_name;
					//Position at 1.5 cm from bottom
					$this->SetY(-15);
					//Arial italic 8
					$this->SetFont('Helvetica','I',8);
					//Page number
					$this->Cell(0,10,'Page '.$this->PageNo().'/'.$comp_name,'T',0,'C');
					$this->SetDrawColor(8, 102, 198);
					parent::Footer();
				}
		}

$pdf=new PDF();
$pdf->AddPage(L);
			
			$pdf->SetXY(10,18);
			$pdf->SetDrawColor(8, 102, 198);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Helvetica','',10);
			$pdf->SetLineWidth(.1);
			$pdf->Cell(0,6,'Salary Sheet','',0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('Helvetica','',9);
			$pdf->Cell(0,6,'For The Month OF '.$ShowDate,'',0,'C',true);
			$pdf->Ln();
			
			$sqls = mysql_query("SELECT * FROM department_info ORDER BY dept_id");
			
			$GrandTotalSalary = 0;
			$GrandTotalOverTime = 0;
			$GrandTotalOtherTips = 0;
			$GrandTotalProvidentFund = 0;
			$GrandTotalPrice = 0;
			
			while ($rows = mysql_fetch_array($sqls)) {
				$dept_id = $rows['dept_id'];
				$dept_name = $rows['dept_name'];
			
				$sql = mysql_query("SELECT e.e_id, e.e_name, e.e_j_date, e.e_des, p.working_day, p.salary, p.over_time, p.other_tips, p.provident_fund, p.note 
									FROM employee_payroll AS p LEFT JOIN emp_info AS e ON e.e_id = p.e_id 
									WHERE e.dept_id = '$dept_id' AND MONTH(p.salary_date) = MONTH('$Date') ORDER BY e.e_name");
				$data = mysql_num_rows($sql);
				if($data == 0){}else{
			
					$pdf->SetX(10);
					$pdf->SetDrawColor(8, 102, 198);
					$pdf->SetFillColor(222, 184, 135);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Helvetica','',9);
					$pdf->SetLineWidth(.1);
					$pdf->Cell(0,6,$dept_name,'LTR',0,'C',true);
					$pdf->Ln();
						
					$pdf->SetDrawColor(8, 102, 198);
					$pdf->SetX(10);
					$pdf->SetFillColor(222, 184, 135);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Helvetica','',9);
					$pdf->SetLineWidth(.1);
					
					$pdf->Cell(10,6,'S/L','LTB',0,'C',true);
					$pdf->Cell(15,6,'DI No','LTB',0,'C',true);
					$pdf->Cell(40,6,'Name','LTB',0,'C',true);
					$pdf->Cell(28,6,'Designation','LTB',0,'C',true);
					$pdf->Cell(20,6,'Joning Date','LTB',0,'C',true);
					$pdf->Cell(20,6,'Working Day','LTB',0,'C',true);
					$pdf->Cell(20,6,'Gross Salary','LTB',0,'C',true);
					$pdf->Cell(20,6,'Over Time','LTB',0,'C',true);
					$pdf->Cell(20,6,'Other Tips','LTB',0,'C',true);
					$pdf->Cell(20,6,'PF','LTB',0,'C',true);
					$pdf->Cell(20,6,'Net Payable','LTB',0,'C',true);
					$pdf->Cell(44,6,'Remarks','LTBR',0,'C',true);
					$pdf->Ln();

					$x = 1;
					$TotalSalary = 0;
					$TotalOverTime = 0;
					$TotalOtherTips = 0;
					$TotalProvidentFund = 0;
					$TotalPrice = 0;
					while ($row = mysql_fetch_array($sql)) {
						$bgcolor = ($x % 2 === 0) ? '255, 255, 255' : '223, 235, 255';

						$GrossSalary = $row['salary'];
						$WorkingDay = $row['working_day'];
						
						$DaySalary = $GrossSalary/30;
						
						$NetSalary = $WorkingDay * $DaySalary;
						
						$Amount = (($NetSalary + $row['over_time'] + $row['other_tips']) - $row['provident_fund']);
						
						$JoinDate = date('d M, Y', strtotime($row['e_j_date']));
						
						$pdf->SetFillColor($bgcolor);
						$pdf->SetDrawColor(8, 102, 198);
						$pdf->SetTextColor(0,0,0);
						$pdf->SetFont('Helvetica','',8); 
						$pdf->SetX(10);
							$pdf->Cell(10,5,$x,'LBT',0,'C',true);
							$pdf->Cell(15,5,$row['e_id'],'LTB',0,'L',true);
							$pdf->Cell(40,5,$row['e_name'],'LTB',0,'L',true);
							$pdf->Cell(28,5,$row['e_des'],'LTB',0,'L',true);
							$pdf->Cell(20,5,$JoinDate,'LTB',0,'C',true);
							$pdf->Cell(20,5,$row['working_day'],'LTB',0,'C',true);
							$pdf->Cell(20,5,$row['salary'],'LTB',0,'R',true);
							$pdf->Cell(20,5,$row['over_time'],'LTB',0,'R',true);
							$pdf->Cell(20,5,$row['other_tips'],'LTB',0,'R',true);
							$pdf->Cell(20,5,$row['provident_fund'],'LTB',0,'R',true);
							$pdf->Cell(20,5,number_format($Amount,2),'LTB',0,'R',true);
							$pdf->Cell(44,5,$row['note'],'LTBR',0,'L',true);
							$pdf->Ln();
						
						$TotalSalary += $row['salary'];
						$TotalOverTime += $row['over_time'];
						$TotalOtherTips += $row['other_tips'];
						$TotalProvidentFund += $row['provident_fund'];
						$TotalPrice += $Amount;
						$x++;	
					}
					
					$pdf->SetDrawColor(8, 102, 198);
					$pdf->SetX(10);
					$pdf->SetFillColor(223, 235, 255);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Helvetica','',9);
					$pdf->SetLineWidth(.1);
					
					$pdf->Cell(133,6,'Total','LTB',0,'C',true);
					$pdf->Cell(20,6,number_format($TotalSalary,2),'LTB',0,'R',true);
					$pdf->Cell(20,6,number_format($TotalOverTime,2),'LTB',0,'R',true);
					$pdf->Cell(20,6,number_format($TotalOtherTips,2),'LTB',0,'R',true);
					$pdf->Cell(20,6,number_format($TotalProvidentFund,2),'LTB',0,'R',true);
					$pdf->Cell(20,6,number_format($TotalPrice,2),'LTB',0,'R',true);
					$pdf->Cell(44,6,'','LTBR',0,'R',true);
					$pdf->Ln();
					
					$GrandTotalSalary += $TotalSalary;
					$GrandTotalOverTime += $TotalOverTime;
					$GrandTotalOtherTips += $TotalOtherTips;
					$GrandTotalProvidentFund += $TotalProvidentFund;
					$GrandTotalPrice += $TotalPrice;
				}
				
				
			}
				$pdf->SetDrawColor(8, 102, 198);
				$pdf->SetX(10);
				$pdf->SetFillColor(222, 184, 135);
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('Helvetica','',9);
				$pdf->SetLineWidth(.1);
				
				$pdf->Cell(133,6,'Grand Total','LTB',0,'C',true);
				$pdf->Cell(20,6,number_format($GrandTotalSalary,2),'LTB',0,'R',true);
				$pdf->Cell(20,6,number_format($GrandTotalOverTime,2),'LTB',0,'R',true);
				$pdf->Cell(20,6,number_format($GrandTotalOtherTips,2),'LTB',0,'R',true);
				$pdf->Cell(20,6,number_format($GrandTotalProvidentFund,2),'LTB',0,'R',true);
				$pdf->Cell(20,6,number_format($GrandTotalPrice,2),'LTB',0,'R',true);
				$pdf->Cell(44,6,'','LTBR',0,'R',true);
				$pdf->Ln();
			
		$pdf->SetDrawColor(8, 102, 198);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetLineWidth(.1);
		$pdf->SetFont('Helvetica','',7);
		$pdf->SetY(-25);
		$pdf->Cell(90,4,'Admin','T',0,'C',true);
		$pdf->Cell(10,4,'','',0,'C',true);
		$pdf->Cell(80,4,'Account','T',0,'C',true);
		$pdf->Cell(10,4,'','',0,'C',true);
		$pdf->Cell(88,4,'Approved By','T',0,'C',true);
		$pdf->Ln();
			
$pdf->Output();
?>