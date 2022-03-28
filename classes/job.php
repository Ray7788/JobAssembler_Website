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

    public static function create_job(string $title, string $description, int $company_id, float $latitude = null, float $longitude = null): bool {
        $pdo = Database::connect();
        if (is_null($latitude) || is_null($longitude)){
            $query = "INSERT INTO JobPostings (Title, Details, CompanyID) VALUES (:title, :description, :company_id)";
            $statement = $pdo->prepare($query);
            return $statement->execute([
                "title" => $title,
                "description" => $description,
                "company_id" => $company_id
            ]);
        }
        else {
            $query = "INSERT INTO JobPostings (Title, Details, CompanyID, Latitude, Longitude) VALUES (:title, :description, :company_id, :latitude, :longitude)";
            $statement = $pdo->prepare($query);
            return $statement->execute([
                "title" => $title,
                "description" => $description,
                "company_id" => $company_id,
                "latitude" => $latitude,
                "longitude" => $longitude
            ]);
        }

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