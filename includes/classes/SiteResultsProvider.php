<?php 

class SiteResultsProvider {

    private $con;


    public function __construct($con){
        $this->con = $con;
    }

    public function getNumResults($term){
        $query = $this->con->prepare("SELECT COUNT(*) as 'total' FROM  sites WHERE title like :term or description like :term or keywords like :term");

        $searchTerm = "%". $term ."%";
        $query->bindParam(":term", $searchTerm);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row["total"];
    }



    public function getResultsHtml($page, $pageSize, $term){


        // if page = 1, so (1 - 1) * $pageSize = 0;
        // if page = 2, entõo (2 - 1) * $pageSize = 20;

        $fromLimit = ($page - 1) * $pageSize;
        $query = $this->con->prepare("

            SELECT * 
            FROM sites 
            WHERE title like :term or description like :term or keywords like :term
            LIMIT :fromLimit, :pageSize
            
            ");
        

        $searchTerm = "%".$term."%";
        $query->bindParam(":term", $searchTerm);
        $query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
        $query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);

        $query->execute();

        $resultHtml = "<div class='linkResults'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $id = $row["id"];
            $title = $row["title"];
            $url = $row["url"];
            $description = $row["description"];
            $keywords = $row["keywords"];
            
            $title = $this->strimField($title, 100);
            $description = $this->strimField($description, 80);
            $url = $this->strimField($url, 55);

            $resultHtml.=
                    "<div class='resultContainer'>
                        <div class='resultContainer'>
                            <h3 class='title'><a href='$url' class='link' data-LinkId='$id'>$title</a></h3>
                            <span class='url'>$url | </span>
                            <span class='description'>$description</span>
                        </div>
                    </div>";
        }

        $resultHtml .= "</div>";

        return $resultHtml;
    } 



    private function strimField($string, $characterLimit){
        $dots = strlen($string) > $characterLimit ? "..." : "";
        return substr($string, 0, $characterLimit) . $dots;
    }
}
?>
 

