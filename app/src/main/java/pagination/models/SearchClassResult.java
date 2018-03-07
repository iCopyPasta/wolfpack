package pagination.models;

import java.util.ArrayList;

/**
 * Created by peo5032 on 3/7/18.
 */

public class SearchClassResult<T> {

    private int currentPage;
    private int totalResults;
    private int totalPages;
    private ArrayList<T> detailedObjects;

    public int getCurrentPage() {
        return currentPage;
    }

    public void setCurrentPage(int currentPage) {
        this.currentPage = currentPage;
    }

    public int getTotalResults() {
        return totalResults;
    }

    public void setTotalResults(int totalResults) {
        this.totalResults = totalResults;
    }

    public int getTotalPages() {
        return totalPages;
    }

    public void setTotalPages(int totalPages) {
        this.totalPages = totalPages;
    }

    public ArrayList<T> getDetailedObjects() {
        return detailedObjects;
    }

    public void setDetailedObjects(ArrayList<T> detailedObjects) {
        this.detailedObjects = detailedObjects;
    }

    public void addAllToDetailsItems(ArrayList<T> newResults){
        this.detailedObjects.addAll(newResults);
    }
}
