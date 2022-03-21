<?php
require_once(__DIR__ . "/company.php");

class Job
{
    public int $id;
    public string $title;
    public string $description;
    public company $company;
    public float $latitude;
    public float $longitude;

    public static function create_job(string $title, string $description, string $location, int $company_id) {
        $pdo = Database::connect();
        $query = "INSERT INTO JobPostings (Title, Details, CompanyID) VALUES (:title, :description, 1)";
        $statement = $pdo->prepare($query);
        return $statement->execute([
            "title" => $title,
            "description" => $description
        ]);
    }

    public function get_job(int $id = null): array {
        if (isset($id)){
            $this->id = $id;
        }
        $pdo = Database::connect();
        $query = "SELECT JobPostings.*, Companies.* FROM JobPostings INNER JOIN Companies ON JobPostings.CompanyID = Companies.CompanyID WHERE JobPostings.JobID = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$this->id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->title = $result["Title"];
        $this->description = $result["Details"];
        $this->latitude = floatval($result["Latitude"]);
        $this->longitude = floatval($result["Longitude"]);
        $this->company = new company();
        $this->company->id = $result["CompanyID"];
        $this->company->name = $result["Name"];
        $this->company->description = $result["Description"];
        if (!is_null($result["CompanyImage"])) {
            $this->company->image_url = $result["CompanyImage"];
        } else {
            $this->company->image_url = "";
        }
        return $result;
    }
}