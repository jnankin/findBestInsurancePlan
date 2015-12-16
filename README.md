# findBestInsurancePlan

If you have a CSV with all the insurance plans you're thinking about, and you have another CSV with all the members of your family and they're expected healthcare expendetures, this little script will sort the plans by total annual spend.

## Installation

    git clone https://github.com/jnankin/findBestInsurancePlan

## Usage
    
    $ php calculateBestPlan.php plans.csv familyMembers.csv

## Output

A sorted table of plans by total annual spend, ascending.

    Plan         Total Premium    Total Extra      Total copay      Total Annual Spend
    G515PPO      $1574.55         $2000.00         $440.00          $21334.60       
    S506PPO      $1354.71         $4950.00         $440.00          $21646.52       
    S506PPO      $1354.71         $4950.00         $440.00          $21646.52       
    S501PPO      $1377.13         $5200.00         $330.00          $22055.56       
    G511PPO      $1615.38         $2800.00         $385.00          $22569.56       
    S503PPO      $1355.52         $6000.00         $330.00          $22596.24       
    S508PPO      $1348.12         $6000.00         $440.00          $22617.44       
    G510PPO      $1576.26         $3600.00         $110.00          $22625.12       
    G517PPO      $1562.57         $3840.00         $220.00          $22810.84       
    G518PPO      $1572.00         $4000.00         $0.00            $22864.00       
    P500PPO      $1826.35         $1600.00         $275.00          $23791.20       
    G509PPO      $1547.53         $6000.00         $165.00          $24735.36   

## Input file format

You can find example input files in the examples directory.  Each file's first row should contain the corresponding titles below (case sensitive).

### plans.csv

Should contain the following columns:

* **Plan** - the name of the healthcare plan
* **Deductible** - the individual deductible in USD (use in network deductible if you have separate deductibles for in or out of network)
* **Copay** - copay in USD per regular visit (not specialist visit - again assume in network for simplicity)
* **Coinsurance** - Percent insurance that INSURANCE COMPANY will pay.  (e.g. 80)
* **OPM** - Individual out of pocket maximum in USD
* **Premium Type** - Either "age" or "total".  If "age", script will look for additional columns that correspond to the monthly premium for each member's age and calculate the total monthly premium.  If "total", will look for a "Premium" column that contains the pre-calculated monthly premium.  If you have the following family members: Josh (31), Julie (30), Jason (6), Ollie (3), Benji (1), the total monthly premium would be the sum of columns ("P_under21" * 3) + P_30 + P_31.  If any of these columns are missing, an exception will be thrown.



### family.csv

Should contain the following columns:

* **Name**
* **DOB** - Date of birth
* **Extra** - How much in USD the family member is estimated to spend in healthcare related costs that are not copays
* **Office Visits** - The expected number of times the member is estimated to pay a copay during the year
